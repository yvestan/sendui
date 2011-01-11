<?php

function bmhOtherRules($result) {

    // pour les auto-réponses
    if (!empty($result['subject']) && !empty($result['from'])) {
        // FIXME: hardcoded!
        $subjects=array('/^Re: Lettre hebdomadaire$/', '/^Out of Office/');
        foreach($subjects as $subject) {
            if (preg_match($subject, $result['subject'])) {
                $result['rule_no']='0253';
                $result['rule_cat']='autoreply';
                $result['bounce_type']='autoreply';
                $result['rule_type'] = 'OTHER';

                if (empty($result['email'])) {
                    $result['email'] = $result['from'];
                }
                break;
            }
        }
    } else if ($result['rule_no'] == '9999') {
        if (substr($result['status_code'],0,4) == '5.1.') {
# X.1.0 Other address status
# X.1.1 Bad destination mailbox address
# X.1.2 Bad destination system address
# X.1.3 Bad destination mailbox address syntax
# X.1.4 Destination mailbox address ambiguous
# X.1.5 Destination mailbox address valid
# X.1.6 Mailbox has moved
# X.1.7 Bad sender's mailbox address syntax
# X.1.8 Bad sender's system address
            $result['bounce_type'] = 'hard';
            $result['rule_no']='0254';
            $result['rule_cat']='unknown';
            $result['rule_type'] = 'OTHER';

        }
    }

    return $result;
}

?>
