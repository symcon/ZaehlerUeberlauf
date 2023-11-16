<?php

declare(strict_types=1);

class ZaehlerUeberlauf extends IPSModule
{
    public function Create()
    {
        //Never delete this line!
        parent::Create();

        $this->RegisterPropertyInteger('SourceVariable', 0);
        $this->RegisterPropertyInteger('MaximumValue', 999999);

        $this->RegisterVariableFloat('Counter', $this->Translate('Counter'), '', 1);
    }

    public function ApplyChanges()
    {

        //Never delete this line!
        parent::ApplyChanges();
        //Deleting references in order to re-add them
        foreach ($this->GetReferenceList() as $referenceID) {
            $this->UnregisterReference($referenceID);
        }

        //Unregister all messages in order to readd them
        foreach ($this->GetMessageList() as $senderID => $messages) {
            foreach ($messages as $message) {
                $this->UnregisterMessage($senderID, $message);
            }
        }

        //Create our trigger
        $sourceVariable = $this->ReadPropertyInteger('SourceVariable');
        if (IPS_VariableExists($sourceVariable)) {
            $this->RegisterMessage($sourceVariable, VM_UPDATE);
            $this->RegisterReference($sourceVariable);
            //Deleting events used in legacy
            $eid = @IPS_GetObjectIDByIdent('SourceTrigger', $this->InstanceID);
            if ($eid) {
                IPS_DeleteEvent($this->GetIDForIdent('SourceTrigger'));
            }
        }
    }

    public function MessageSink($TimeStamp, $SenderID, $Message, $Data)
    {
        //VM_UPDATE
        $this->Update($Data[2], $Data[0]);
    }

    private function Update(float $OldValue, float $Value)
    {
        if (($Value - $OldValue) < 0) {
            $diff = $this->ReadPropertyInteger('MaximumValue') + 1 - $OldValue + $Value;
        } else {
            $diff = $Value - $OldValue;
        }

        //update value
        SetValue($this->GetIDForIdent('Counter'), GetValue($this->GetIDForIdent('Counter')) + $diff);
    }
}