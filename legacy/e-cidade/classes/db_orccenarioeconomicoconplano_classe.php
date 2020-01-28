<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: orcamento
//CLASSE DA ENTIDADE orccenarioeconomicoconplano
class cl_orccenarioeconomicoconplano { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $o04_sequencial = 0; 
   var $o04_orccenarioeconomicoparam = 0; 
   var $o04_conplano = 0; 
   var $o04_anousu = 0; 
   var $o04_tipocalculo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o04_sequencial = int4 = C�digo Sequencial 
                 o04_orccenarioeconomicoparam = int4 = Parametro Macroeconomico 
                 o04_conplano = int4 = Conta 
                 o04_anousu = int4 = Ano 
                 o04_tipocalculo = int4 = Tipo de C�lculo 
                 ";
   //funcao construtor da classe 
   function cl_orccenarioeconomicoconplano() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orccenarioeconomicoconplano"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->o04_sequencial = ($this->o04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o04_sequencial"]:$this->o04_sequencial);
       $this->o04_orccenarioeconomicoparam = ($this->o04_orccenarioeconomicoparam == ""?@$GLOBALS["HTTP_POST_VARS"]["o04_orccenarioeconomicoparam"]:$this->o04_orccenarioeconomicoparam);
       $this->o04_conplano = ($this->o04_conplano == ""?@$GLOBALS["HTTP_POST_VARS"]["o04_conplano"]:$this->o04_conplano);
       $this->o04_anousu = ($this->o04_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o04_anousu"]:$this->o04_anousu);
       $this->o04_tipocalculo = ($this->o04_tipocalculo == ""?@$GLOBALS["HTTP_POST_VARS"]["o04_tipocalculo"]:$this->o04_tipocalculo);
     }else{
       $this->o04_sequencial = ($this->o04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o04_sequencial"]:$this->o04_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($o04_sequencial){ 
      $this->atualizacampos();
     if($this->o04_orccenarioeconomicoparam == null ){ 
       $this->erro_sql = " Campo Parametro Macroeconomico nao Informado.";
       $this->erro_campo = "o04_orccenarioeconomicoparam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o04_conplano == null ){ 
       $this->erro_sql = " Campo Conta nao Informado.";
       $this->erro_campo = "o04_conplano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o04_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "o04_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o04_tipocalculo == null ){ 
       $this->o04_tipocalculo = 1;
     }
     if($o04_sequencial == "" || $o04_sequencial == null ){
       $result = db_query("select nextval('orccenarioeconomicoconplano_o04_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orccenarioeconomicoconplano_o04_sequencial_seq do campo: o04_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o04_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orccenarioeconomicoconplano_o04_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o04_sequencial)){
         $this->erro_sql = " Campo o04_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o04_sequencial = $o04_sequencial; 
       }
     }
     if(($this->o04_sequencial == null) || ($this->o04_sequencial == "") ){ 
       $this->erro_sql = " Campo o04_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orccenarioeconomicoconplano(
                                       o04_sequencial 
                                      ,o04_orccenarioeconomicoparam 
                                      ,o04_conplano 
                                      ,o04_anousu 
                                      ,o04_tipocalculo 
                       )
                values (
                                $this->o04_sequencial 
                               ,$this->o04_orccenarioeconomicoparam 
                               ,$this->o04_conplano 
                               ,$this->o04_anousu 
                               ,$this->o04_tipocalculo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "desdepas/receitas  vinculadas  ($this->o04_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "desdepas/receitas  vinculadas  j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "desdepas/receitas  vinculadas  ($this->o04_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o04_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->o04_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13593,'$this->o04_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2381,13593,'','".AddSlashes(pg_result($resaco,0,'o04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2381,13594,'','".AddSlashes(pg_result($resaco,0,'o04_orccenarioeconomicoparam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2381,13595,'','".AddSlashes(pg_result($resaco,0,'o04_conplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2381,13596,'','".AddSlashes(pg_result($resaco,0,'o04_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2381,19867,'','".AddSlashes(pg_result($resaco,0,'o04_tipocalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o04_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update orccenarioeconomicoconplano set ";
     $virgula = "";
     if(trim($this->o04_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o04_sequencial"])){ 
       $sql  .= $virgula." o04_sequencial = $this->o04_sequencial ";
       $virgula = ",";
       if(trim($this->o04_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo Sequencial nao Informado.";
         $this->erro_campo = "o04_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o04_orccenarioeconomicoparam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o04_orccenarioeconomicoparam"])){ 
       $sql  .= $virgula." o04_orccenarioeconomicoparam = $this->o04_orccenarioeconomicoparam ";
       $virgula = ",";
       if(trim($this->o04_orccenarioeconomicoparam) == null ){ 
         $this->erro_sql = " Campo Parametro Macroeconomico nao Informado.";
         $this->erro_campo = "o04_orccenarioeconomicoparam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o04_conplano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o04_conplano"])){ 
       $sql  .= $virgula." o04_conplano = $this->o04_conplano ";
       $virgula = ",";
       if(trim($this->o04_conplano) == null ){ 
         $this->erro_sql = " Campo Conta nao Informado.";
         $this->erro_campo = "o04_conplano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o04_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o04_anousu"])){ 
       $sql  .= $virgula." o04_anousu = $this->o04_anousu ";
       $virgula = ",";
       if(trim($this->o04_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "o04_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o04_tipocalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o04_tipocalculo"])){ 
       $sql  .= $virgula." o04_tipocalculo = $this->o04_tipocalculo ";
       $virgula = ",";
       if(trim($this->o04_tipocalculo) == null ){ 
         $this->erro_sql = " Campo Tipo de C�lculo nao Informado.";
         $this->erro_campo = "o04_tipocalculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o04_sequencial!=null){
       $sql .= " o04_sequencial = $this->o04_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->o04_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,13593,'$this->o04_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o04_sequencial"]) || $this->o04_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2381,13593,'".AddSlashes(pg_result($resaco,$conresaco,'o04_sequencial'))."','$this->o04_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o04_orccenarioeconomicoparam"]) || $this->o04_orccenarioeconomicoparam != "")
             $resac = db_query("insert into db_acount values($acount,2381,13594,'".AddSlashes(pg_result($resaco,$conresaco,'o04_orccenarioeconomicoparam'))."','$this->o04_orccenarioeconomicoparam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o04_conplano"]) || $this->o04_conplano != "")
             $resac = db_query("insert into db_acount values($acount,2381,13595,'".AddSlashes(pg_result($resaco,$conresaco,'o04_conplano'))."','$this->o04_conplano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o04_anousu"]) || $this->o04_anousu != "")
             $resac = db_query("insert into db_acount values($acount,2381,13596,'".AddSlashes(pg_result($resaco,$conresaco,'o04_anousu'))."','$this->o04_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["o04_tipocalculo"]) || $this->o04_tipocalculo != "")
             $resac = db_query("insert into db_acount values($acount,2381,19867,'".AddSlashes(pg_result($resaco,$conresaco,'o04_tipocalculo'))."','$this->o04_tipocalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "desdepas/receitas  vinculadas  nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o04_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "desdepas/receitas  vinculadas  nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o04_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o04_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o04_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($o04_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,13593,'$o04_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2381,13593,'','".AddSlashes(pg_result($resaco,$iresaco,'o04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2381,13594,'','".AddSlashes(pg_result($resaco,$iresaco,'o04_orccenarioeconomicoparam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2381,13595,'','".AddSlashes(pg_result($resaco,$iresaco,'o04_conplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2381,13596,'','".AddSlashes(pg_result($resaco,$iresaco,'o04_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2381,19867,'','".AddSlashes(pg_result($resaco,$iresaco,'o04_tipocalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from orccenarioeconomicoconplano
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o04_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o04_sequencial = $o04_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "desdepas/receitas  vinculadas  nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o04_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "desdepas/receitas  vinculadas  nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o04_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o04_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:orccenarioeconomicoconplano";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from orccenarioeconomicoconplano ";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = orccenarioeconomicoconplano.o04_conplano and  conplano.c60_anousu = orccenarioeconomicoconplano.o04_anousu";
     $sql .= "      inner join orccenarioeconomicoparam  on  orccenarioeconomicoparam.o03_sequencial = orccenarioeconomicoconplano.o04_orccenarioeconomicoparam";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join orccenarioeconomico  as a on   a.o02_sequencial = orccenarioeconomicoparam.o03_orccenarioeconomico";
     $sql2 = "";
     if($dbwhere==""){
       if($o04_sequencial!=null ){
         $sql2 .= " where orccenarioeconomicoconplano.o04_sequencial = $o04_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $o04_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from orccenarioeconomicoconplano ";
     $sql2 = "";
     if($dbwhere==""){
       if($o04_sequencial!=null ){
         $sql2 .= " where orccenarioeconomicoconplano.o04_sequencial = $o04_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>