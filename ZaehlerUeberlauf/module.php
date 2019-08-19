<?

	class ZaehlerUeberlauf extends IPSModule
	{
		
		public function Create()
		{
			//Never delete this line!
			parent::Create();
			
			$this->RegisterPropertyInteger("SourceVariable", 0);
			$this->RegisterPropertyInteger("MaximumValue", 999999);
			
			$this->RegisterVariableFloat("Counter", "Counter", "", 1);
		}
	
		public function ApplyChanges()
		{
			
			//Never delete this line!
			parent::ApplyChanges();
			
			//Create our trigger
			if(IPS_VariableExists($this->ReadPropertyInteger("SourceVariable"))) {
				$this->RegisterMessage($this->ReadPropertyInteger("SourceVariable"), VM_UPDATE);
				$eid = @IPS_GetObjectIDByIdent("SourceTrigger", $this->InstanceID);
				if($eid) {
					IPS_DeleteEvent($this->GetIDForIdent("SourceTrigger"));
				}
			}
			
		}
	
		private function Update(int $OldValue, int $Value)
		{
			if (($Value - $OldValue) < 0) {
				$diff = $this->ReadPropertyInteger("MaximumValue") + 1 - $OldValue + $Value;
			} else {
				$diff = $Value - $OldValue;
			}
			
			//update value
			SetValue($this->GetIDForIdent("Counter"), GetValue($this->GetIDForIdent("Counter")) + $diff);
		
		}
	
		public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
		{
			$this->Update($Data[2], $Data[0]);
		}
	}

?>
