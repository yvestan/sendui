<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Classe de template pour message personnalisé
 *
 * Remplace les valeurs {_(test)_} 
 *
 * Déclaration :
 *
 * <code>
 *   $row = array(
 *       'email' => 'moi@test.com',
 *       'nom' => 'NOM',
 *       'prenom' => 'PRENOM',
 *   );
 * </code>
 *
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence GPL Gnu Public Licence
 * @version      0.1.0
 */

class Template {

    // les variables
	protected $variables = array();

    // le pattern de remplacement
	protected $pattern = '#{_\(([a-z0-9_]+)\)_}#imSU';

    // {{{ __construct()

    /** Constructeur
     * 
     * Passe le tableau des variables
     *
     */
	public function __construct($variables=array())
    {
		$this->variables = $variables; 
	}

    // }}}

    // {{{ parse()

    /** Remplacement
     * 
     * @param string $in Valeur d'entrée
     * @return string
     */
	public function parse($in) 
    {
		return preg_replace_callback($this->pattern, array($this, 'replace'), $in);
	}

    // }}}

    // {{{ setVariables()

    /** Tableau de coresspondance
     * 
     * @param array $variables Tableau de valeurs
     * @return array
     */
	protected function setVariables($variables=array())
    {
		$this->variables = $variables;
	}
    
    // }}}
		
    // {{{ replace()

    /** Fonction de callback
     * 
     * @param array $matches Valeurs matchées
     * @return string
     */
	protected function replace($matches)
    {
		if (isset($this->variables[$matches[1]]))
			return $this->variables[$matches[1]];
		else
			return '';
	}

    // }}}

}
?>
