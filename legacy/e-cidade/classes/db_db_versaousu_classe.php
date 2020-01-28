<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_versaousu
class cl_db_versaousu { 
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
   var $db32_codusu = 0; 
   var $db32_codver = 0; 
   var $db32_id_item = 0; 
   var $db32_obs = null; 
   var $db32_obsdb = null; 
   var $db32_data_dia = null; 
   var $db32_data_mes = null; 
   var $db32_data_ano = null; 
   var $db32_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db32_codusu = int4 = Código da Observação 
                 db32_codver = int4 = Código da Versão 
                 db32_id_item = int4 = Item de Menu 
                 db32_obs = text = Observações para o usuário 
                 db32_obsdb = text = Observação DBSeller 
                 db32_data = date = Data da inclusão 
                 ";
   //funcao construtor da classe 
   function cl_db_versaousu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_versaousu"); 
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
       $this->db32_codusu = ($this->db32_codusu == ""?@$GLOBALS["HTTP_POST_VARS"]["db32_codusu"]:$this->db32_codusu);
       $this->db32_codver = ($this->db32_codver == ""?@$GLOBALS["HTTP_POST_VARS"]["db32_codver"]:$this->db32_codver);
       $this->db32_id_item = ($this->db32_id_item == ""?@$GLOBALS["HTTP_POST_VARS"]["db32_id_item"]:$this->db32_id_item);
       $this->db32_obs = ($this->db32_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["db32_obs"]:$this->db32_obs);
       $this->db32_obsdb = ($this->db32_obsdb == ""?@$GLOBALS["HTTP_POST_VARS"]["db32_obsdb"]:$this->db32_obsdb);
       if($this->db32_data == ""){
         $this->db32_data_dia = ($this->db32_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db32_data_dia"]:$this->db32_data_dia);
         $this->db32_data_mes = ($this->db32_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db32_data_mes"]:$this->db32_data_mes);
         $this->db32_data_ano = ($this->db32_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db32_data_ano"]:$this->db32_data_ano);
         if($this->db32_data_dia != ""){
            $this->db32_data = $this->db32_data_ano."-".$this->db32_data_mes."-".$this->db32_data_dia;
         }
       }
     }else{
       $this->db32_codusu = ($this->db32_codusu == ""?@$GLOBALS["HTTP_POST_VARS"]["db32_codusu"]:$this->db32_codusu);
     }
   }
   // funcao para inclusao
   function incluir ($db32_codusu){ 
      $this->atualizacampos();
     if($this->db32_codver == null ){ 
       $this->erro_sql = " Campo Código da Versão nao Informado.";
       $this->erro_campo = "db32_codver";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db32_id_item == null ){ 
       $this->erro_sql = " Campo Item de Menu nao Informado.";
       $this->erro_campo = "db32_id_item";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db32_obs == null ){ 
       $this->erro_sql = " Campo Observações para o usuário nao Informado.";
       $this->erro_campo = "db32_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db32_obsdb == null ){ 
       $this->erro_sql = " Campo Observação DBSeller nao Informado.";
       $this->erro_campo = "db32_obsdb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db32_data == null ){ 
       $this->erro_sql = " Campo Data da inclusão nao Informado.";
       $this->erro_campo = "db32_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db32_codusu == "" || $db32_codusu == null ){
       $result = db_query("select nextval('db_versaousu_db32_codusu_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_versaousu_db32_codusu_seq do campo: db32_codusu"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db32_codusu = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_versaousu_db32_codusu_seq");
       if(($result != false) && (pg_result($result,0,0) < $db32_codusu)){
         $this->erro_sql = " Campo db32_codusu maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db32_codusu = $db32_codusu; 
       }
     }
     if(($this->db32_codusu == null) || ($this->db32_codusu == "") ){ 
       $this->erro_sql = " Campo db32_codusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_versaousu(
                                       db32_codusu 
                                      ,db32_codver 
                                      ,db32_id_item 
                                      ,db32_obs 
                                      ,db32_obsdb 
                                      ,db32_data 
                       )
                values (
                                $this->db32_codusu 
                               ,$this->db32_codver 
                               ,$this->db32_id_item 
                               ,'$this->db32_obs' 
                               ,'$this->db32_obsdb' 
                               ,".($this->db32_data == "null" || $this->db32_data == ""?"null":"'".$this->db32_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Versão Para o Usuário ($this->db32_codusu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Versão Para o Usuário já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Versão Para o Usuário ($this->db32_codusu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db32_codusu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db32_codusu));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5878,'$this->db32_codusu','I')");
       $resac = db_query("insert into db_acount values($acount,940,5878,'','".AddSlashes(pg_result($resaco,0,'db32_codusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,940,5874,'','".AddSlashes(pg_result($resaco,0,'db32_codver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,940,5875,'','".AddSlashes(pg_result($resaco,0,'db32_id_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,940,5876,'','".AddSlashes(pg_result($resaco,0,'db32_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,940,7403,'','".AddSlashes(pg_result($resaco,0,'db32_obsdb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,940,5877,'','".AddSlashes(pg_result($resaco,0,'db32_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db32_codusu=null) { 
      $this->atualizacampos();
     $sql = " update db_versaousu set ";
     $virgula = "";
     if(trim($this->db32_codusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db32_codusu"])){ 
       $sql  .= $virgula." db32_codusu = $this->db32_codusu ";
       $virgula = ",";
       if(trim($this->db32_codusu) == null ){ 
         $this->erro_sql = " Campo Código da Observação nao Informado.";
         $this->erro_campo = "db32_codusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db32_codver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db32_codver"])){ 
       $sql  .= $virgula." db32_codver = $this->db32_codver ";
       $virgula = ",";
       if(trim($this->db32_codver) == null ){ 
         $this->erro_sql = " Campo Código da Versão nao Informado.";
         $this->erro_campo = "db32_codver";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db32_id_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db32_id_item"])){ 
       $sql  .= $virgula." db32_id_item = $this->db32_id_item ";
       $virgula = ",";
       if(trim($this->db32_id_item) == null ){ 
         $this->erro_sql = " Campo Item de Menu nao Informado.";
         $this->erro_campo = "db32_id_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db32_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db32_obs"])){ 
       $sql  .= $virgula." db32_obs = '$this->db32_obs' ";
       $virgula = ",";
       if(trim($this->db32_obs) == null ){ 
         $this->erro_sql = " Campo Observações para o usuário nao Informado.";
         $this->erro_campo = "db32_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db32_obsdb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db32_obsdb"])){ 
       $sql  .= $virgula." db32_obsdb = '$this->db32_obsdb' ";
       $virgula = ",";
       if(trim($this->db32_obsdb) == null ){ 
         $this->erro_sql = " Campo Observação DBSeller nao Informado.";
         $this->erro_campo = "db32_obsdb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db32_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db32_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db32_data_dia"] !="") ){ 
       $sql  .= $virgula." db32_data = '$this->db32_data' ";
       $virgula = ",";
       if(trim($this->db32_data) == null ){ 
         $this->erro_sql = " Campo Data da inclusão nao Informado.";
         $this->erro_campo = "db32_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db32_data_dia"])){ 
         $sql  .= $virgula." db32_data = null ";
         $virgula = ",";
         if(trim($this->db32_data) == null ){ 
           $this->erro_sql = " Campo Data da inclusão nao Informado.";
           $this->erro_campo = "db32_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($db32_codusu!=null){
       $sql .= " db32_codusu = $this->db32_codusu";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db32_codusu));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5878,'$this->db32_codusu','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db32_codusu"]))
           $resac = db_query("insert into db_acount values($acount,940,5878,'".AddSlashes(pg_result($resaco,$conresaco,'db32_codusu'))."','$this->db32_codusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db32_codver"]))
           $resac = db_query("insert into db_acount values($acount,940,5874,'".AddSlashes(pg_result($resaco,$conresaco,'db32_codver'))."','$this->db32_codver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db32_id_item"]))
           $resac = db_query("insert into db_acount values($acount,940,5875,'".AddSlashes(pg_result($resaco,$conresaco,'db32_id_item'))."','$this->db32_id_item',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db32_obs"]))
           $resac = db_query("insert into db_acount values($acount,940,5876,'".AddSlashes(pg_result($resaco,$conresaco,'db32_obs'))."','$this->db32_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db32_obsdb"]))
           $resac = db_query("insert into db_acount values($acount,940,7403,'".AddSlashes(pg_result($resaco,$conresaco,'db32_obsdb'))."','$this->db32_obsdb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db32_data"]))
           $resac = db_query("insert into db_acount values($acount,940,5877,'".AddSlashes(pg_result($resaco,$conresaco,'db32_data'))."','$this->db32_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Versão Para o Usuário nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db32_codusu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Versão Para o Usuário nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db32_codusu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db32_codusu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db32_codusu=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db32_codusu));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5878,'$db32_codusu','E')");
         $resac = db_query("insert into db_acount values($acount,940,5878,'','".AddSlashes(pg_result($resaco,$iresaco,'db32_codusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,940,5874,'','".AddSlashes(pg_result($resaco,$iresaco,'db32_codver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,940,5875,'','".AddSlashes(pg_result($resaco,$iresaco,'db32_id_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,940,5876,'','".AddSlashes(pg_result($resaco,$iresaco,'db32_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,940,7403,'','".AddSlashes(pg_result($resaco,$iresaco,'db32_obsdb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,940,5877,'','".AddSlashes(pg_result($resaco,$iresaco,'db32_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_versaousu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db32_codusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db32_codusu = $db32_codusu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Versão Para o Usuário nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db32_codusu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Versão Para o Usuário nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db32_codusu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db32_codusu;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_versaousu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db32_codusu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_versaousu ";
     $sql .= "      inner join db_itensmenu  on  db_itensmenu.id_item = db_versaousu.db32_id_item";
     $sql .= "      inner join db_versao  on  db_versao.db30_codver = db_versaousu.db32_codver";
     $sql2 = "";
     if($dbwhere==""){
       if($db32_codusu!=null ){
         $sql2 .= " where db_versaousu.db32_codusu = $db32_codusu "; 
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
   function sql_query_file ( $db32_codusu=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_versaousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($db32_codusu!=null ){
         $sql2 .= " where db_versaousu.db32_codusu = $db32_codusu "; 
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