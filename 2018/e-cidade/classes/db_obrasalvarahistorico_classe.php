<?
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: projetos
//CLASSE DA ENTIDADE obrasalvarahistorico
class cl_obrasalvarahistorico { 
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
   var $ob35_sequencial = 0; 
   var $ob35_codobra = 0; 
   var $ob35_datainicial_dia = null; 
   var $ob35_datainicial_mes = null; 
   var $ob35_datainicial_ano = null; 
   var $ob35_datainicial = null; 
   var $ob35_datafinal_dia = null; 
   var $ob35_datafinal_mes = null; 
   var $ob35_datafinal_ano = null; 
   var $ob35_datafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ob35_sequencial = int4 = Sequencial 
                 ob35_codobra = int4 = C�digo da Obra 
                 ob35_datainicial = date = Data Inicial 
                 ob35_datafinal = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_obrasalvarahistorico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("obrasalvarahistorico"); 
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
       $this->ob35_sequencial = ($this->ob35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ob35_sequencial"]:$this->ob35_sequencial);
       $this->ob35_codobra = ($this->ob35_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob35_codobra"]:$this->ob35_codobra);
       if($this->ob35_datainicial == ""){
         $this->ob35_datainicial_dia = ($this->ob35_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob35_datainicial_dia"]:$this->ob35_datainicial_dia);
         $this->ob35_datainicial_mes = ($this->ob35_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob35_datainicial_mes"]:$this->ob35_datainicial_mes);
         $this->ob35_datainicial_ano = ($this->ob35_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob35_datainicial_ano"]:$this->ob35_datainicial_ano);
         if($this->ob35_datainicial_dia != ""){
            $this->ob35_datainicial = $this->ob35_datainicial_ano."-".$this->ob35_datainicial_mes."-".$this->ob35_datainicial_dia;
         }
       }
       if($this->ob35_datafinal == ""){
         $this->ob35_datafinal_dia = ($this->ob35_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob35_datafinal_dia"]:$this->ob35_datafinal_dia);
         $this->ob35_datafinal_mes = ($this->ob35_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob35_datafinal_mes"]:$this->ob35_datafinal_mes);
         $this->ob35_datafinal_ano = ($this->ob35_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob35_datafinal_ano"]:$this->ob35_datafinal_ano);
         if($this->ob35_datafinal_dia != ""){
            $this->ob35_datafinal = $this->ob35_datafinal_ano."-".$this->ob35_datafinal_mes."-".$this->ob35_datafinal_dia;
         }
       }
     }else{
       $this->ob35_sequencial = ($this->ob35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ob35_sequencial"]:$this->ob35_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ob35_sequencial){ 
      $this->atualizacampos();
     if($this->ob35_codobra == null ){ 
       $this->erro_sql = " Campo C�digo da Obra n�o informado.";
       $this->erro_campo = "ob35_codobra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob35_datainicial == null ){ 
       $this->erro_sql = " Campo Data Inicial n�o informado.";
       $this->erro_campo = "ob35_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ob35_sequencial == "" || $ob35_sequencial == null ){
       $result = db_query("select nextval('obrasalvarahistorico_ob35_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: obrasalvarahistorico_ob35_sequencial_seq do campo: ob35_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ob35_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from obrasalvarahistorico_ob35_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ob35_sequencial)){
         $this->erro_sql = " Campo ob35_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ob35_sequencial = $ob35_sequencial; 
       }
     }
     if(($this->ob35_sequencial == null) || ($this->ob35_sequencial == "") ){ 
       $this->erro_sql = " Campo ob35_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obrasalvarahistorico(
                                       ob35_sequencial 
                                      ,ob35_codobra 
                                      ,ob35_datainicial 
                                      ,ob35_datafinal 
                       )
                values (
                                $this->ob35_sequencial 
                               ,$this->ob35_codobra 
                               ,".($this->ob35_datainicial == "null" || $this->ob35_datainicial == ""?"null":"'".$this->ob35_datainicial."'")." 
                               ,".($this->ob35_datafinal == "null" || $this->ob35_datafinal == ""?"null":"'".$this->ob35_datafinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Hist�rico dos alvar�s ($this->ob35_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Hist�rico dos alvar�s j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Hist�rico dos alvar�s ($this->ob35_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob35_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ob35_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20457,'$this->ob35_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3679,20457,'','".AddSlashes(pg_result($resaco,0,'ob35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3679,20458,'','".AddSlashes(pg_result($resaco,0,'ob35_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3679,20459,'','".AddSlashes(pg_result($resaco,0,'ob35_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3679,20460,'','".AddSlashes(pg_result($resaco,0,'ob35_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ob35_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update obrasalvarahistorico set ";
     $virgula = "";
     if(trim($this->ob35_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob35_sequencial"])){ 
       $sql  .= $virgula." ob35_sequencial = $this->ob35_sequencial ";
       $virgula = ",";
       if(trim($this->ob35_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial n�o informado.";
         $this->erro_campo = "ob35_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob35_codobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob35_codobra"])){ 
       $sql  .= $virgula." ob35_codobra = $this->ob35_codobra ";
       $virgula = ",";
       if(trim($this->ob35_codobra) == null ){ 
         $this->erro_sql = " Campo C�digo da Obra n�o informado.";
         $this->erro_campo = "ob35_codobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob35_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob35_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob35_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ob35_datainicial = '$this->ob35_datainicial' ";
       $virgula = ",";
       if(trim($this->ob35_datainicial) == null ){ 
         $this->erro_sql = " Campo Data Inicial n�o informado.";
         $this->erro_campo = "ob35_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob35_datainicial_dia"])){ 
         $sql  .= $virgula." ob35_datainicial = null ";
         $virgula = ",";
         if(trim($this->ob35_datainicial) == null ){ 
           $this->erro_sql = " Campo Data Inicial n�o informado.";
           $this->erro_campo = "ob35_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ob35_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob35_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob35_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." ob35_datafinal = '$this->ob35_datafinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob35_datafinal_dia"])){ 
         $sql  .= $virgula." ob35_datafinal = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($ob35_sequencial!=null){
       $sql .= " ob35_sequencial = $this->ob35_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ob35_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20457,'$this->ob35_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob35_sequencial"]) || $this->ob35_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3679,20457,'".AddSlashes(pg_result($resaco,$conresaco,'ob35_sequencial'))."','$this->ob35_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob35_codobra"]) || $this->ob35_codobra != "")
             $resac = db_query("insert into db_acount values($acount,3679,20458,'".AddSlashes(pg_result($resaco,$conresaco,'ob35_codobra'))."','$this->ob35_codobra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob35_datainicial"]) || $this->ob35_datainicial != "")
             $resac = db_query("insert into db_acount values($acount,3679,20459,'".AddSlashes(pg_result($resaco,$conresaco,'ob35_datainicial'))."','$this->ob35_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ob35_datafinal"]) || $this->ob35_datafinal != "")
             $resac = db_query("insert into db_acount values($acount,3679,20460,'".AddSlashes(pg_result($resaco,$conresaco,'ob35_datafinal'))."','$this->ob35_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Hist�rico dos alvar�s nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob35_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Hist�rico dos alvar�s nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob35_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ob35_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ob35_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ob35_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20457,'$ob35_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3679,20457,'','".AddSlashes(pg_result($resaco,$iresaco,'ob35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3679,20458,'','".AddSlashes(pg_result($resaco,$iresaco,'ob35_codobra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3679,20459,'','".AddSlashes(pg_result($resaco,$iresaco,'ob35_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3679,20460,'','".AddSlashes(pg_result($resaco,$iresaco,'ob35_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from obrasalvarahistorico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ob35_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ob35_sequencial = $ob35_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Hist�rico dos alvar�s nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob35_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Hist�rico dos alvar�s nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob35_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ob35_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:obrasalvarahistorico";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ob35_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasalvarahistorico ";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obrasalvarahistorico.ob35_codobra";
     $sql .= "      inner join obrastiporesp  on  obrastiporesp.ob02_cod = obras.ob01_tiporesp";
     $sql2 = "";
     if($dbwhere==""){
       if($ob35_sequencial!=null ){
         $sql2 .= " where obrasalvarahistorico.ob35_sequencial = $ob35_sequencial "; 
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
   function sql_query_file ( $ob35_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from obrasalvarahistorico ";
     $sql2 = "";
     if($dbwhere==""){
       if($ob35_sequencial!=null ){
         $sql2 .= " where obrasalvarahistorico.ob35_sequencial = $ob35_sequencial "; 
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