<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Classe de gestion des tables temporaires de destinataires
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */

class Batch {

    // le message
    protected $idmessage;

    // les abonnés
    protected $dao_subscriber = 'common~subscriber';

    // l'instance jDb et jDb widget
    protected $db;
    protected $dbw;

    // nom des tables batch
    protected $batch_table_prefix = 'batch_message_';
    protected $batch_table;

    // {{{ __construct()

   /** constructeur
    *
    * @access   public
    * @return   bool
    */
    public function __construct($idmessage)
    {

        // message
        $this->idmessage = (int)$idmessage;

        // table
        $this->batch_table = $this->batch_table_prefix.$this->idmessage;

        // instance jDb
        $this->db = jDb::getConnection();
        $this->dbw = jDb::getDbWidget();

    }

    // }}}

    // {{{ getStructureTable()

   /** 
    *
    * @access   public
    * @return   bool
    */
    public function getStructureTable($action='CREATE')
    {

        $fields  = '
          `idsubscriber` int(10) unsigned NOT NULL auto_increment,
          `idcustomer` int(10) unsigned default NULL,
          `token` varchar(50) default NULL,
          `email` varchar(150) NOT NULL,
          `fullname` varchar(50) default NULL,
          `firstname` varchar(50) default NULL,
          `lastname` varchar(50) default NULL,
          `phone` varchar(50) default NULL,
          `mobile` varchar(50) default NULL,
          `address` varchar(250) default NULL,
          `zip` varchar(15) default NULL,
          `city` varchar(100) default NULL,
          `country` char(2) default NULL,
          `status` tinyint(1) default \'0\',
          `confirmed` timestamp NULL default NULL,
          `html_format` tinyint(1) NOT NULL,
          `text_format` tinyint(1) NOT NULL,
          `subscribe_from` varchar(50) NOT NULL,
          `sent_date` timestamp NULL default NULL,
          `sent` tinyint(1) default NULL,
          `date_update` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
          `date_insert` timestamp NULL default NULL,';

        $keys = '
          PRIMARY KEY  (`idsubscriber`),
          KEY `token` (`token`),
          KEY `email` (`email`),
          KEY `status` (`status`),
          KEY `confirmed` (`confirmed`),
          KEY `idcustomer` (`idcustomer`),
          KEY `sent` (`sent`)';

        $engine = ' ENGINE=InnoDB  DEFAULT CHARSET=utf8';

        if($action=='CREATE'){
            return 'CREATE TABLE `'.$this->batch_table.'` ('.$fields.$keys.') '.$engine;
        }

    }

    // {{{ isTable()

   /** vérifier si la table batch existe pour le message
    *
    * @access   public
    * @return   bool
    */
    public function isTable()
    {
        
        $sql = 'SHOW TABLES FROM '.$this->db->profile['database'].' LIKE \''.$this->batch_table.'\' ';

        // requete et résult dans un tableau
        $rs = $this->db->query($sql);
        $record = $rs->fetchAll();

        return !empty($record);

    }

    // }}}

    // {{{ createTable()

   /** créer la table batch si elle n'existe pas
    *
    * @access   public
    * @return   bool
    */
    public function createTable()
    {

        $sql = $this->getStructureTable('CREATE');
        return $this->db->exec($sql);

    }

    // }}}

    // {{{ getSQLSubscribers()

   /** requête pour récupérer les bons abonnés
    *
    * @access   public
    * @return   string
    */
    public function getSQLSubscribers()
    {

        // copier uniquement les utilisateurs qui sont actifs pour la liste du message
        $sql =  'SELECT s.* FROM subscriber s, message_subscriber_list msl
                    WHERE s.idsubscriber_list=msl.idsubscriber_list
                    AND s.status=1
                    AND msl.idmessage='.$this->idmessage;

        return $sql;

    }

    // }}}

    // {{{ deleteTable()

   /** supprimer la table batch
    *
    * @access   public
    * @return   bool
    */
    public function deleteTable()
    {

        $msg = null;

        if($this->isTable()) {
            $sql = 'DROP TABLE '.$this->batch_table;
            return $this->db->exec($sql);
        } else {
            $msg = 'La table '.$this->batch_table.' n\'existe pas';
        }

        return $msg;

    }

    // }}}

    // {{{ truncateTable()

   /** vider la table batch
    *
    * @access   public
    * @return   bool
    */
    public function truncateTable()
    {

        $sql = 'TRUNCATE TABLE '.$this->batch_table.$this->idmessage;
        return $this->db->exec($sql);

    }

    // }}}

    // {{{ copyTable()

   /** créer la table et copier les données
    *
    * @access   public
    * @return   bool
    */
    public function copyTable()
    {

        $sql = $this->getStructureTable('CREATE').' AS ('.$this->getSQLSubscribers().')';
        return $this->db->exec($sql);

    }

    // }}}

    // {{{ getSubscribers()

   /** récupérer les destinataires du message
    *
    * @access   public
    * @return   bool
    */
    public function getSubscribers($sent=null)
    {

        $sql = 'SELECT * FROM '.$this->batch_table.' 
                WHERE email IS NOT NULL ';

        // envoyé ou tous
        if(is_null($sent)) {
            $sql .= ' AND sent IS NULL';
        } elseif ($sent==1) {
            $sql .= ' AND sent=1';    
        }

        $rs = $this->db->query($sql);
        //$rs->setFetchMode($rs->FETCH_CLASS , 'subscriber');

        return $rs;

    }

    // }}}

    // {{{ countSubscribers()

   /** compter le nb de subscribers dans la table batch
    *
    * @access   public
    * @return   bool
    */
    public function countSubscribers($sent='all')
    {

        if($this->isTable()) {

            $sql = 'SELECT COUNT(*) as nb 
                    FROM '.$this->batch_table.' 
                    WHERE email IS NOT NULL ';

            // envoyé ou tous
            if($sent!='all') {
                if($sent==0) {
                    $sql .= ' AND sent=0';
                } elseif ($sent==1) {
                    $sql .= ' AND sent=1';    
                }
            }

            $rs = $this->dbw->fetchFirst($sql);

            return $rs->nb;

        }

        return 0;    

    }

    // }}}

    // {{{ countSubscribersSent()

   /** compter le nb de subscribers  déjà envoyé
    *
    * @access   public
    * @return   bool
    */
    public function countSubscribersSent() { return $this->countSubscribers(1); }

    // }}}

    // {{{ countSubscribersNoSent()

   /** compter le nb de subscribers  pas encore envoyé
    *
    * @access   public
    * @return   bool
    */
    public function countSubscribersNoSent() { return $this->countSubscribers(0); }

    // }}}

    // {{{ updateSent()

   /** mettre à jour le chap envoyé
    *
    * @access   public
    * @return   bool
    */
    public function updateSent($idsubscriber)
    {

        $sql = 'UPDATE '.$this->batch_table.'
                SET sent=1, sent_date=NOW() WHERE idsubscriber='.(int)$idsubscriber;

        return $this->db->exec($sql);

    }

    // }}}
    
    // {{{ resetSent()

   /** mettre à jour le chap envoyé
    *
    * @access   public
    * @return   bool
    */
    public function resetSent()
    {

        $sql = 'UPDATE '.$this->batch_table.'
                SET sent=NULL, sent_date=NULL ';

        return $this->db->exec($sql);

    }

    // }}}

    // {{{ getSubscriberTableName()

   /** nom de la table subscriber
    *
    * @access   public
    * @return   bool
    */
    public function getSubscriberTableName()
    {

        // dao subscribers
        $subscriber = jDao::get($this->dao_subscriber);

        $properties = $subscriber->getProperties();
        return $properties['idsubscriber']['table'];

    }

    // }}}

}
?>
