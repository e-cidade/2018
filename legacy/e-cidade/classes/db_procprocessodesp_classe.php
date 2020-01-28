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

//MODULO: protocolo
//CLASSE DA ENTIDADE procprocessodesp
class cl_procprocessodesp { 
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
   var $p80_coddesp = 0; 
   var $p80_codigo = 0; 
   var $p80_id_usuario = 0; 
   var $p80_data_dia = null; 
   var $p80_data_mes = null; 
   var $p80_data_ano = null; 
   var $p80_data = null; 
   var $p80_hora = null; 
   var $p80_despacho = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p80_coddesp = int4 = Código Despacho 
                 p80_codigo = int4 = Código 
                 p80_id_usuario = int4 = Usuário 
                 p80_data = date = Data 
                 p80_hora = varchar(5) = Hora 
                 p80_despacho = text = Despacho 
                 ";
   //funcao construtor da classe 
   function cl_procprocessodesp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("procprocessodesp"); 
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
       $this->p80_coddesp = ($this->p80_coddesp == ""?@$GLOBALS["HTTP_POST_VARS"]["p80_coddesp"]:$this->p80_coddesp);
       $this->p80_codigo = ($this->p80_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p80_codigo"]:$this->p80_codigo);
       $this->p80_id_usuario = ($this->p80_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["p80_id_usuario"]:$this->p80_id_usuario);
       if($this->p80_data == ""){
         $this->p80_data_dia = ($this->p80_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p80_data_dia"]:$this->p80_data_dia);
         $this->p80_data_mes = ($this->p80_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p80_data_mes"]:$this->p80_data_mes);
         $this->p80_data_ano = ($this->p80_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p80_data_ano"]:$this->p80_data_ano);
         if($this->p80_data_dia != ""){
            $this->p80_data = $this->p80_data_ano."-".$this->p80_data_mes."-".$this->p80_data_dia;
         }
       }
       $this->p80_hora = ($this->p80_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["p80_hora"]:$this->p80_hora);
       $this->p80_despacho = ($this->p80_despacho == ""?@$GLOBALS["HTTP_POST_VARS"]["p80_despacho"]:$this->p80_despacho);
     }else{
       $this->p80_coddesp = ($this->p80_coddesp == ""?@$GLOBALS["HTTP_POST_VARS"]["p80_coddesp"]:$this->p80_coddesp);
     }
   }
   // funcao para inclusao
   function incluir ($p80_coddesp){ 
      $this->atualizacampos();
     if($this->p80_codigo == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "p80_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p80_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "p80_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p80_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "p80_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p80_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "p80_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p80_despacho == null ){ 
       $this->erro_sql = " Campo Despacho nao Informado.";
       $this->erro_campo = "p80_despacho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p80_coddesp == "" || $p80_coddesp == null ){
       $result = db_query("select nextval('procprocessodesp_p80_coddesp_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: procprocessodesp_p80_coddesp_seq do campo: p80_coddesp"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p80_coddesp = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from procprocessodesp_p80_coddesp_seq");
       if(($result != false) && (pg_result($result,0,0) < $p80_coddesp)){
         $this->erro_sql = " Campo p80_coddesp maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p80_coddesp = $p80_coddesp; 
       }
     }
     if(($this->p80_coddesp == null) || ($this->p80_coddesp == "") ){ 
       $this->erro_sql = " Campo p80_coddesp nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into procprocessodesp(
                                       p80_coddesp 
                                      ,p80_codigo 
                                      ,p80_id_usuario 
                                      ,p80_data 
                                      ,p80_hora 
                                      ,p80_despacho 
                       )
                values (
                                $this->p80_coddesp 
                               ,$this->p80_codigo 
                               ,$this->p80_id_usuario 
                               ,".($this->p80_data == "null" || $this->p80_data == ""?"null":"'".$this->p80_data."'")." 
                               ,'$this->p80_hora' 
                               ,'$this->p80_despacho' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Despachos do Processo ($this->p80_coddesp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Despachos do Processo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Despachos do Processo ($this->p80_coddesp) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p80_coddesp;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p80_coddesp));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1697,'$this->p80_coddesp','I')");
       $resac = db_query("insert into db_acount values($acount,283,1697,'','".AddSlashes(pg_result($resaco,0,'p80_coddesp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,283,1693,'','".AddSlashes(pg_result($resaco,0,'p80_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,283,1695,'','".AddSlashes(pg_result($resaco,0,'p80_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,283,1694,'','".AddSlashes(pg_result($resaco,0,'p80_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,283,1696,'','".AddSlashes(pg_result($resaco,0,'p80_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,283,6161,'','".AddSlashes(pg_result($resaco,0,'p80_despacho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p80_coddesp=null) { 
      $this->atualizacampos();
     $sql = " update procprocessodesp set ";
     $virgula = "";
     if(trim($this->p80_coddesp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p80_coddesp"])){ 
       $sql  .= $virgula." p80_coddesp = $this->p80_coddesp ";
       $virgula = ",";
       if(trim($this->p80_coddesp) == null ){ 
         $this->erro_sql = " Campo Código Despacho nao Informado.";
         $this->erro_campo = "p80_coddesp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p80_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p80_codigo"])){ 
       $sql  .= $virgula." p80_codigo = $this->p80_codigo ";
       $virgula = ",";
       if(trim($this->p80_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "p80_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p80_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p80_id_usuario"])){ 
       $sql  .= $virgula." p80_id_usuario = $this->p80_id_usuario ";
       $virgula = ",";
       if(trim($this->p80_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "p80_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p80_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p80_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p80_data_dia"] !="") ){ 
       $sql  .= $virgula." p80_data = '$this->p80_data' ";
       $virgula = ",";
       if(trim($this->p80_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "p80_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p80_data_dia"])){ 
         $sql  .= $virgula." p80_data = null ";
         $virgula = ",";
         if(trim($this->p80_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "p80_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p80_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p80_hora"])){ 
       $sql  .= $virgula." p80_hora = '$this->p80_hora' ";
       $virgula = ",";
       if(trim($this->p80_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "p80_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p80_despacho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p80_despacho"])){ 
       $sql  .= $virgula." p80_despacho = '$this->p80_despacho' ";
       $virgula = ",";
       if(trim($this->p80_despacho) == null ){ 
         $this->erro_sql = " Campo Despacho nao Informado.";
         $this->erro_campo = "p80_despacho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p80_coddesp!=null){
       $sql .= " p80_coddesp = $this->p80_coddesp";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p80_coddesp));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1697,'$this->p80_coddesp','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p80_coddesp"]))
           $resac = db_query("insert into db_acount values($acount,283,1697,'".AddSlashes(pg_result($resaco,$conresaco,'p80_coddesp'))."','$this->p80_coddesp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p80_codigo"]))
           $resac = db_query("insert into db_acount values($acount,283,1693,'".AddSlashes(pg_result($resaco,$conresaco,'p80_codigo'))."','$this->p80_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p80_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,283,1695,'".AddSlashes(pg_result($resaco,$conresaco,'p80_id_usuario'))."','$this->p80_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p80_data"]))
           $resac = db_query("insert into db_acount values($acount,283,1694,'".AddSlashes(pg_result($resaco,$conresaco,'p80_data'))."','$this->p80_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p80_hora"]))
           $resac = db_query("insert into db_acount values($acount,283,1696,'".AddSlashes(pg_result($resaco,$conresaco,'p80_hora'))."','$this->p80_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p80_despacho"]))
           $resac = db_query("insert into db_acount values($acount,283,6161,'".AddSlashes(pg_result($resaco,$conresaco,'p80_despacho'))."','$this->p80_despacho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Despachos do Processo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p80_coddesp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Despachos do Processo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p80_coddesp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p80_coddesp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p80_coddesp=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p80_coddesp));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1697,'$p80_coddesp','E')");
         $resac = db_query("insert into db_acount values($acount,283,1697,'','".AddSlashes(pg_result($resaco,$iresaco,'p80_coddesp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,283,1693,'','".AddSlashes(pg_result($resaco,$iresaco,'p80_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,283,1695,'','".AddSlashes(pg_result($resaco,$iresaco,'p80_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,283,1694,'','".AddSlashes(pg_result($resaco,$iresaco,'p80_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,283,1696,'','".AddSlashes(pg_result($resaco,$iresaco,'p80_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,283,6161,'','".AddSlashes(pg_result($resaco,$iresaco,'p80_despacho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from procprocessodesp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p80_coddesp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p80_coddesp = $p80_coddesp ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Despachos do Processo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p80_coddesp;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Despachos do Processo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p80_coddesp;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p80_coddesp;
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
        $this->erro_sql   = "Record Vazio na Tabela:procprocessodesp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $p80_coddesp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procprocessodesp ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = procprocessodesp.p80_id_usuario";
     $sql .= "      inner join processo  on  processo.p02_codigo = procprocessodesp.p80_codigo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = processo.p02_numcgm";
     $sql .= "      inner join depto  on  depto.p04_codigo = processo.p02_andami";
     $sql .= "      inner join tipo  on  tipo.p01_codigo = processo.p02_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($p80_coddesp!=null ){
         $sql2 .= " where procprocessodesp.p80_coddesp = $p80_coddesp "; 
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
   function sql_query_file ( $p80_coddesp=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from procprocessodesp ";
     $sql2 = "";
     if($dbwhere==""){
       if($p80_coddesp!=null ){
         $sql2 .= " where procprocessodesp.p80_coddesp = $p80_coddesp "; 
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