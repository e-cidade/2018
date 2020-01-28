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

//MODULO: transporteescolar
//CLASSE DA ENTIDADE itinerariologradouro
class cl_itinerariologradouro { 
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
   var $tre10_sequencial = 0; 
   var $tre10_linhatransporteitinerario = 0; 
   var $tre10_cadenderbairrocadenderrua = 0; 
   var $tre10_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tre10_sequencial = int4 = Sequencial 
                 tre10_linhatransporteitinerario = int4 = Sequencial 
                 tre10_cadenderbairrocadenderrua = int4 = Sequencial 
                 tre10_ordem = int4 = Ordem 
                 ";
   //funcao construtor da classe 
   function cl_itinerariologradouro() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itinerariologradouro"); 
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
       $this->tre10_sequencial = ($this->tre10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tre10_sequencial"]:$this->tre10_sequencial);
       $this->tre10_linhatransporteitinerario = ($this->tre10_linhatransporteitinerario == ""?@$GLOBALS["HTTP_POST_VARS"]["tre10_linhatransporteitinerario"]:$this->tre10_linhatransporteitinerario);
       $this->tre10_cadenderbairrocadenderrua = ($this->tre10_cadenderbairrocadenderrua == ""?@$GLOBALS["HTTP_POST_VARS"]["tre10_cadenderbairrocadenderrua"]:$this->tre10_cadenderbairrocadenderrua);
       $this->tre10_ordem = ($this->tre10_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["tre10_ordem"]:$this->tre10_ordem);
     }else{
       $this->tre10_sequencial = ($this->tre10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tre10_sequencial"]:$this->tre10_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($tre10_sequencial){ 
      $this->atualizacampos();
     if($this->tre10_linhatransporteitinerario == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "tre10_linhatransporteitinerario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tre10_cadenderbairrocadenderrua == null ){ 
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "tre10_cadenderbairrocadenderrua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tre10_ordem == null ){ 
       $this->erro_sql = " Campo Ordem nao Informado.";
       $this->erro_campo = "tre10_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tre10_sequencial == "" || $tre10_sequencial == null ){
       $result = db_query("select nextval('itinerariologradouro_tre10_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itinerariologradouro_tre10_sequencial_seq do campo: tre10_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tre10_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from itinerariologradouro_tre10_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $tre10_sequencial)){
         $this->erro_sql = " Campo tre10_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tre10_sequencial = $tre10_sequencial; 
       }
     }
     if(($this->tre10_sequencial == null) || ($this->tre10_sequencial == "") ){ 
       $this->erro_sql = " Campo tre10_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itinerariologradouro(
                                       tre10_sequencial 
                                      ,tre10_linhatransporteitinerario 
                                      ,tre10_cadenderbairrocadenderrua 
                                      ,tre10_ordem 
                       )
                values (
                                $this->tre10_sequencial 
                               ,$this->tre10_linhatransporteitinerario 
                               ,$this->tre10_cadenderbairrocadenderrua 
                               ,$this->tre10_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itinerário Logradouro ($this->tre10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itinerário Logradouro já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itinerário Logradouro ($this->tre10_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tre10_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tre10_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20106,'$this->tre10_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3607,20106,'','".AddSlashes(pg_result($resaco,0,'tre10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3607,20107,'','".AddSlashes(pg_result($resaco,0,'tre10_linhatransporteitinerario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3607,20108,'','".AddSlashes(pg_result($resaco,0,'tre10_cadenderbairrocadenderrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3607,20109,'','".AddSlashes(pg_result($resaco,0,'tre10_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tre10_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update itinerariologradouro set ";
     $virgula = "";
     if(trim($this->tre10_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre10_sequencial"])){ 
       $sql  .= $virgula." tre10_sequencial = $this->tre10_sequencial ";
       $virgula = ",";
       if(trim($this->tre10_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "tre10_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre10_linhatransporteitinerario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre10_linhatransporteitinerario"])){ 
       $sql  .= $virgula." tre10_linhatransporteitinerario = $this->tre10_linhatransporteitinerario ";
       $virgula = ",";
       if(trim($this->tre10_linhatransporteitinerario) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "tre10_linhatransporteitinerario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre10_cadenderbairrocadenderrua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre10_cadenderbairrocadenderrua"])){ 
       $sql  .= $virgula." tre10_cadenderbairrocadenderrua = $this->tre10_cadenderbairrocadenderrua ";
       $virgula = ",";
       if(trim($this->tre10_cadenderbairrocadenderrua) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "tre10_cadenderbairrocadenderrua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tre10_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tre10_ordem"])){ 
       $sql  .= $virgula." tre10_ordem = $this->tre10_ordem ";
       $virgula = ",";
       if(trim($this->tre10_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "tre10_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tre10_sequencial!=null){
       $sql .= " tre10_sequencial = $this->tre10_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tre10_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20106,'$this->tre10_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre10_sequencial"]) || $this->tre10_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3607,20106,'".AddSlashes(pg_result($resaco,$conresaco,'tre10_sequencial'))."','$this->tre10_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre10_linhatransporteitinerario"]) || $this->tre10_linhatransporteitinerario != "")
             $resac = db_query("insert into db_acount values($acount,3607,20107,'".AddSlashes(pg_result($resaco,$conresaco,'tre10_linhatransporteitinerario'))."','$this->tre10_linhatransporteitinerario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre10_cadenderbairrocadenderrua"]) || $this->tre10_cadenderbairrocadenderrua != "")
             $resac = db_query("insert into db_acount values($acount,3607,20108,'".AddSlashes(pg_result($resaco,$conresaco,'tre10_cadenderbairrocadenderrua'))."','$this->tre10_cadenderbairrocadenderrua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tre10_ordem"]) || $this->tre10_ordem != "")
             $resac = db_query("insert into db_acount values($acount,3607,20109,'".AddSlashes(pg_result($resaco,$conresaco,'tre10_ordem'))."','$this->tre10_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itinerário Logradouro nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tre10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itinerário Logradouro nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tre10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tre10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tre10_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($tre10_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20106,'$tre10_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3607,20106,'','".AddSlashes(pg_result($resaco,$iresaco,'tre10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3607,20107,'','".AddSlashes(pg_result($resaco,$iresaco,'tre10_linhatransporteitinerario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3607,20108,'','".AddSlashes(pg_result($resaco,$iresaco,'tre10_cadenderbairrocadenderrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3607,20109,'','".AddSlashes(pg_result($resaco,$iresaco,'tre10_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from itinerariologradouro
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tre10_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tre10_sequencial = $tre10_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itinerário Logradouro nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tre10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itinerário Logradouro nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tre10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tre10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:itinerariologradouro";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tre10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itinerariologradouro ";
     $sql .= "      inner join cadenderbairrocadenderrua  on  cadenderbairrocadenderrua.db87_sequencial = itinerariologradouro.tre10_cadenderbairrocadenderrua";
     $sql .= "      inner join linhatransporteitinerario  on  linhatransporteitinerario.tre09_sequencial = itinerariologradouro.tre10_linhatransporteitinerario";
     $sql .= "      inner join cadenderbairro  on  cadenderbairro.db73_sequencial = cadenderbairrocadenderrua.db87_cadenderbairro";
     $sql .= "      inner join cadenderrua  on  cadenderrua.db74_sequencial = cadenderbairrocadenderrua.db87_cadenderrua";
     $sql .= "      inner join linhatransporte  as a on   a.tre06_sequencial = linhatransporteitinerario.tre09_linhatransporte";
     $sql2 = "";
     if($dbwhere==""){
       if($tre10_sequencial!=null ){
         $sql2 .= " where itinerariologradouro.tre10_sequencial = $tre10_sequencial "; 
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
   function sql_query_file ( $tre10_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itinerariologradouro ";
     $sql2 = "";
     if($dbwhere==""){
       if($tre10_sequencial!=null ){
         $sql2 .= " where itinerariologradouro.tre10_sequencial = $tre10_sequencial "; 
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