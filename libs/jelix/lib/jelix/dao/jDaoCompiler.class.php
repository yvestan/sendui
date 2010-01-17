<?php
/**
* @package    jelix
* @subpackage dao
* @author      Laurent Jouanneau
* @contributor
* @copyright   2005-2007 Laurent Jouanneau
* Idea of this class was get originally from the Copix project
* (CopixDaoCompiler, Copix 2.3dev20050901, http://www.copix.org)
* no more line of code are copyrighted by CopixTeam
*
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 *
 */
require(JELIX_LIB_PATH.'dao/jDaoParser.class.php');
require(JELIX_LIB_PATH.'dao/jDaoProperty.class.php');
require(JELIX_LIB_PATH.'dao/jDaoMethod.class.php');
require(JELIX_LIB_PATH.'dao/jDaoGenerator.class.php');

/**
 * The compiler for the DAO xml files. it is used by jIncluder
 * It produces some php classes
 * @package  jelix
 * @subpackage dao
 */
class jDaoCompiler  implements jISimpleCompiler {
    /**
    * the current DAO id.
    * @var string
    */
    static public $daoId = '';

    /**
     * the current DAO file path
     * @var string
     */
    static public $daoPath = '';

    /**
     * The database type
     * @var string
     */
    static public $dbType='';

    /**
    * compile the given class id.
    */
    public function compile ($selector) {

        jDaoCompiler::$daoId = $selector->toString();
        jDaoCompiler::$daoPath = $selector->getPath();
        jDaoCompiler::$dbType = $selector->driver;

        // chargement du fichier XML
        $doc = new DOMDocument();

        if(!$doc->load(jDaoCompiler::$daoPath)){
            throw new jException('jelix~daoxml.file.unknow', jDaoCompiler::$daoPath);
        }

        if($doc->documentElement->namespaceURI != JELIX_NAMESPACE_BASE.'dao/1.0'){
            throw new jException('jelix~daoxml.namespace.wrong',array(jDaoCompiler::$daoPath, $doc->namespaceURI));
        }

        $parser = new jDaoParser ();
        $parser->parse(simplexml_import_dom($doc));

        global $gJConfig;
        if(!isset($gJConfig->_pluginsPathList_db[$selector->driver])
            || !file_exists($gJConfig->_pluginsPathList_db[$selector->driver]) ){
            throw new jException('jelix~db.error.driver.notfound', $selector->driver);
        }
        require_once($gJConfig->_pluginsPathList_db[$selector->driver].$selector->driver.'.daobuilder.php');
        $class = $selector->driver.'DaoBuilder';
        $generator = new $class ($selector->getDaoClass(), $selector->getDaoRecordClass(), $parser);

        // génération des classes PHP correspondant à la définition de la DAO
        $compiled = '<?php '.$generator->buildClasses ()."\n?>";
        jFile::write ($selector->getCompiledFilePath(), $compiled);
        return true;
    }
}

/**
 * Exception for Dao compiler
 * @package  jelix
 * @subpackage dao
 */
class jDaoXmlException extends jException {

    /**
     * @param string $localekey a locale key
     * @param array $localeParams parameters for the message (for sprintf)
     */
    public function __construct($localekey, $localeParams=array()) {
        $localekey= 'jelix~daoxml.'.$localekey;

        $arg=array(jDaoCompiler::$daoId, jDaoCompiler::$daoPath);
        if(is_array($localeParams)){
            $arg=array_merge($arg, $localeParams);
        }else{
            $arg[]=$localeParams;
        }
        parent::__construct($localekey, $arg);
    }
}
