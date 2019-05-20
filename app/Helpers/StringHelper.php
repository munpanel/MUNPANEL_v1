<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

/**
 * Extract mentioned users from string
 */
function extract_mention($text)
{
        preg_match_all('/@\(([0-9]+?)\)/', $text, $match);

        if(empty($match))
                return array();

        $users = array();
        foreach($match[1] as $user)
        {
            $user = \App\User::find(intval($user));
            if (empty($user))
                continue;
            // make sure it has a legal role to be mentioned
            $regs = $user->regs;
            foreach($regs as $reg)
            {
                if($reg->conference_id == Reg::currentConferenceID() && in_array($reg->type, ['ot', 'dais', 'interviewer']))
                {
                    $users[] = $user;
                    break;
                }
            }
        }

        return array_unique($users);
}
