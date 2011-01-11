<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Classe d'utilitaires
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */

class Utils {

    // {{{ getTimeExec()

   /** Calcul le temps d'execution du script
    *
    * @access   public
    * @return   string
    * @param    string  $start  Début
    * @param    string  $end    Fin
    */
    public function getTimeExec($start,$end)
    {
        $timestamp = abs($end - $start);
        $diff_heure = floor($timestamp / 3600);
        $timestamp = $timestamp - ($diff_heure * 3600);
        $diff_min = $timestamp / 60;
        return $diff_heure.' heure(s) '.round($diff_min,1).' minute(s)';
    }

    // }}}

    // {{{ isEmailSyntaxValid()

   /** Contrôle la validité de l'adresse email
    *
    * Auteur : bobocop (arobase) bobocop (point) cz
    * Traduction des commentaires par mathieu
    *
    * Le code suivant est la version du 2 mai 2005 qui respecte les RFC 2822 et 1035
    * http://www.faqs.org/rfcs/rfc2822.html
    * http://www.faqs.org/rfcs/rfc1035.html
    *
    * @access   public
    * @return   bool
    * @param    string  $email  Adresse email
    */
    public function isEmailSyntaxValid($email)
    {

        $atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
        $domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)
                                       
        $regex = '/^' . $atom . '+' .   // Une ou plusieurs fois les caractères autorisés avant l'arobase
        '(\.' . $atom . '+)*' .         // Suivis par zéro point ou plus
                                        // séparés par des caractères autorisés avant l'arobase
        '@' .                           // Suivis d'un arobase
        '(' . $domain . '{1,63}\.)+' .  // Suivis par 1 à 63 caractères autorisés pour le nom de domaine
                                        // séparés par des points
        $domain . '{2,63}$/i';          // Suivi de 2 à 63 caractères autorisés pour le nom de domaine
        return preg_match($regex,$email);

    }

    // }}}

    // {{{ getExternalContent()

    /** Récupérer depuis un fichier distant
     * 
     * @param string $URL URL du fichier
     * 
     * @return string
     */
    public function getExternalContent($URL)
    {

        // init de CURL
        $ch = curl_init($URL);    

        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // le contenu
        $return = curl_exec($ch);

        return $return;
    }

    // }}}

}
?>
