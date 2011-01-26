<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 ff=unix fenc=utf8: */

/**
 * Classe de gestion des tables en ajax
 *
 * @package      sendui
 * @subpackage   sendui
 * @author       Yves Tannier [grafactory.net]
 * @copyright    2009 Yves Tannier
 * @link         http://www.grafactory.net/sendui
 * @license      http://www.grafactory.net/sendui/licence MIT Licence
 * @version      0.1.0
 */

class Datatables {

    // les colonnes
    protected $columns = array();

    // le dao concerné
    protected $dao = null;

    // l'index autre que le primary
    protected $index_column = null;

    // nombre total d'enregitrement
    protected $total_records = null;

    // nombre d'enregistrement affichés
    protected $total_display_records = null;

    // le resultset
    protected $result_set = null;

    // {{{ setColumns()

    /** Définir les colonnes sur lesquels on recherche
     * 
     * @params array $columns les nom des colonnes
     */
    public function setColumns($columns)
    { 
        $this->columns = $columns;
    }

    // }}}

    // {{{ setColumns()

    /** Définir l'index pour le count et le select distinct
     * Par défaut, la clé primaire du DAO
     * 
     * @params string $index l'index
     */
    public function setIndex($index)
    {
        $this->index_column = $index;
    }

    // }}}

    // {{{ setDao()

    /** Définir le DAO de recherche
     *
     * 
     * @params string $dao le DAO
     */
    public function setDao($dao)
    {
        $this->dao = $dao;
    }

    // }}}

    // {{{ getResults()

    /** Lancer la recherche
     *
     * @return $object retourne un result set
     */
    public function getResults()
    {
       
        // dao
        $factory = jDao::get($this->dao);

        // conditions
        $conditions = jDao::createConditions();
        
        // macth global
        if($_GET['sSearch'] != '') {
            $conditions->startGroup('OR');
            for ($i=0 ; $i<count($this->columns) ; $i++) {
                $conditions->addCondition($this->columns[$i],'LIKE','%'.$_GET['sSearch'].'%');
            }
            $conditions->endGroup(); 
        }
        
        // filtre sur une colonne
        for($i=0 ; $i<count($this->columns) ; $i++) {
            if($_GET['bSearchable_'.$i] == 'true' && $_GET['sSearch_'.$i] != '') {
                $conditions->addCondition($this->columns[$i],'LIKE','%'.$_GET['sSearch_'.$i].'%');
            }
        }

        if(!empty($_GET['idsubscriber_list'])) {
            $conditions->addCondition('idsubscriber_list','=',(int)$_GET['idsubscriber_list']);
        }

        // ordering
        if(isset($_GET['iSortCol_0'] )) {
            for($i=0 ; $i<intval($_GET['iSortingCols']); $i++) {
                if($_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i])]=='true') {
                    $conditions->addItemOrder($this->columns[intval($_GET['iSortCol_'.$i])],$_GET['sSortDir_'.$i]);
                }
            }
        }

        // Paging
        if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength']!='-1') {
            $this->result_set = $factory->findBy($conditions, $_GET['iDisplayStart'], $_GET['iDisplayLength']);
        } else {
            $this->result_set = $factory->findBy($conditions);
        }

        /// totaux
        $this->setTotalDisplayRecords($factory->countBy($conditions, $this->index_column));
        $this->setTotalRecords($factory->countBy($conditions, $this->index_column));
        
        return $this->result_set;

    }

    // }}}

    // {{{ getOutputInfos()

    /** Le tableau nécessaire à datatables
     *
     * @param array $datas Le résults set
     * @return $array
     */
    public function getOutputInfos($datas=array())
    {

        $output = array(
            'sEcho' => intval($_GET['sEcho']),
            'iTotalRecords' => $this->getTotalRecords(),
            'aaData' => array()
        );

        if(empty($datas)) {
            foreach($this->result_set as $aRow) {
                $row = array();
                for ($i=0 ; $i<count($this->columns) ;$i++) {
                    if ($this->columns[$i]=='version') {
                        /* Special output formatting for 'version' column */
                        $row[] = ($aRow->{$this->columns[$i]}=='0') ? '-' : $aRow->{$this->columns[$i]};
                    } elseif ($this->columns[$i] != ' ') {
                        /* General output */
                        $row[] = $aRow->{$this->columns[$i]};
                    }
                }
                $output['aaData'][] = $row;
            }
        } else {
            $output['aaData'] = $datas;
        }

        // nb de résultat avec les filtres ET le limit
        //$output['iTotalDisplayRecords'] = count($output['aaData']);
        $output['iTotalDisplayRecords'] = $this->getTotalDisplayRecords();

        return $output;

    }

    // }}}

    // {{{ getTotalRecords()

    /** Renvoyer le nombre de résultat brut
     *
     * @return int
     */
    public function getTotalRecords()
    { 
        return $this->total_records;
    }

    // }}}

    // {{{ setTotalRecords()

    /** Définir le nombre de résultat brut
     *
     * @params int $total_records Nombre de résultats
     */
    public function setTotalRecords($total_records) 
    { 
        $this->total_records = $total_records;
    }

    // }}}

    // {{{ getTotalDisplayRecords()

    /** Renvoyer le nombre de résultat filtré
     *
     * @return int
     */
    public function getTotalDisplayRecords() 
    { 
        return $this->total_display_records; 
    }

    // }}}

    // {{{ setTotalDisplayRecords()

    /** Définir le nombre de résultat filtré
     *
     * @params int $total_display_records Nombre de résultats
     */
    public function setTotalDisplayRecords($total_display_records) 
    { 
        $this->total_display_records = $total_display_records; 
    }

    // }}}

}
