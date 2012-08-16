<?

/*

  Payment routines.

*/

class Action_Payment extends Action_Reg
{

    function _common() {
#        Profiler::disable();
    }

	function pay($i) {
        if ( Debug::is_prod() ) {
            if (!$_SERVER['HTTP_HTTPS']) { //if not on secured server - redirect
                $cook="/*".$_COOKIE["uid"];
                $url="https://".$_SERVER['HTTP_HOST'].$cook.$_SERVER['HTTP_X_URI'];
                go($url);
            }
	    }

        $this->invoice=Me()->Fin->get_invoice($i);
		return '/payment/pay';
	}

	/*
	 * $report_pid is an id of profile that customer should be redirected to after payment is completed.
	 * $report_pid will be stored in Me()->Session to be available after return from payment server
	 *
	 * $webcall_pid, $webcall_phone - profile id & phone number that customer was trying to call to
	 * $webcall_pid, $webcall_phone will be stored in Me()->Session to be available after return from payment server
	 */
	function my_account() {

		$report_pid = empty($this->_['report_pid'])?0:$this->_['report_pid'];
		$webcall_pid = empty($this->_['webcall_pid'])?0:$this->_['webcall_pid'];
		$webcall_phone = empty($this->_['webcall_phone'])?0:$this->_['webcall_phone'];

		if ($report_pid)
			Me()->Session->report_pid = $report_pid;

		if ($webcall_pid && $webcall_phone) {
			Me()->Session->webcall = array('webcall_pid' => $webcall_pid, 'webcall_phone' => $webcall_phone);
		}

		if ($report_pid = Me()->Session->report_pid) {
			if (Acxiom::report_price() <= Me()->Fin->balance  ) {
				Me()->Session->report_pid = NULL;
				Me()->Session->do_purchase = $report_pid;
				go('/P/?id='.$report_pid);
			}
		}

		if (! ($webcall_pid && $webcall_phone) ) { #it is not first lanting on this page from web call popup dialog
			if ($webcall = Me()->Session->webcall) { #if desired webcall information is in session
				if (Me()->Fin->balance >= 3) {//$3 is a minimum balance after adding funds
					Me()->Session->webcall = NULL;
					Me()->Session->do_webcall = $webcall;
					go('/P/?id='.$webcall['webcall_pid']);
				}
			}
		}

		return '/payment/my_account';
	}

	function to_payment($amount) {
	    $invoice=Me()->Fin->get_pending_invoice(str_replace('$','',$amount));
	    $cook="/*".$_COOKIE["uid"];
		if(Debug::is_dvp())
			$url="/payment/pay?i=$invoice";
	    else
			$url="https://".$_SERVER['HTTP_HOST'].$cook."/payment/pay?i=$invoice";

	    header("Location: $url");
		return 0;
	}

    function c_csc() {
        return array("help_dlg_body" => tpl("Payment/CSC_Description"));
    }

}

?>
