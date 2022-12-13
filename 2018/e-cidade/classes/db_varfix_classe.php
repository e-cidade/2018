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

//MODULO: issqn
//CLASSE DA ENTIDADE varfix
class cl_varfix { 
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
   var $q33_codigo = 0; 
   var $q33_inscr = 0; 
   var $q33_tiporeg = null; 
   var $q33_data_dia = null; 
   var $q33_data_mes = null; 
   var $q33_data_ano = null; 
   var $q33_data = null; 
   var $q33_hora = null; 
   var $q33_obs = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q33_codigo = int4 = Código 
                 q33_inscr = int4 = inscricao 
                 q33_tiporeg = char(1) = Tipo de regime 
                 q33_data = date = Data 
                 q33_hora = varchar(5) = Hora 
                 q33_obs = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_varfix() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("varfix"); 
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
       $this->q33_codigo = ($this->q33_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q33_codigo"]:$this->q33_codigo);
       $this->q33_inscr = ($this->q33_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q33_inscr"]:$this->q33_inscr);
       $this->q33_tiporeg = ($this->q33_tiporeg == ""?@$GLOBALS["HTTP_POST_VARS"]["q33_tiporeg"]:$this->q33_tiporeg);
       if($this->q33_data == ""){
         $this->q33_data_dia = ($this->q33_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["q33_data_dia"]:$this->q33_data_dia);
         $this->q33_data_mes = ($this->q33_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q33_data_mes"]:$this->q33_data_mes);
         $this->q33_data_ano = ($this->q33_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q33_data_ano"]:$this->q33_data_ano);
         if($this->q33_data_dia != ""){
            $this->q33_data = $this->q33_data_ano."-".$this->q33_data_mes."-".$this->q33_data_dia;
         }
       }
       $this->q33_hora = ($this->q33_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["q33_hora"]:$this->q33_hora);
       $this->q33_obs = ($this->q33_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["q33_obs"]:$this->q33_obs);
     }else{
       $this->q33_codigo = ($this->q33_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q33_codigo"]:$this->q33_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($q33_codigo){ 
      $this->atualizacampos();
     if($this->q33_inscr == null ){ 
       $this->erro_sql = " Campo inscricao nao Informado.";
       $this->erro_campo = "q33_inscr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q33_tiporeg == null ){ 
       $this->erro_sql = " Campo Tipo de regime nao Informado.";
       $this->erro_campo = "q33_tiporeg";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q33_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "q33_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q33_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "q33_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q33_codigo == "" || $q33_codigo == null ){
       $result = db_query("select nextval('varfix_q33_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: varfix_q33_codigo_seq do campo: q33_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q33_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from varfix_q33_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $q33_codigo)){
         $this->erro_sql = " Campo q33_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q33_codigo = $q33_codigo; 
       }
     }
     if(($this->q33_codigo == null) || ($this->q33_codigo == "") ){ 
       $this->erro_sql = " Campo q33_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into varfix(
                                       q33_codigo 
                                      ,q33_inscr 
                                      ,q33_tiporeg 
                                      ,q33_data 
                                      ,q33_hora 
                                      ,q33_obs 
                       )
                values (
                                $this->q33_codigo 
                               ,$this->q33_inscr 
                               ,'$this->q33_tiporeg' 
                               ,".($this->q33_data == "null" || $this->q33_data == ""?"null":"'".$this->q33_data."'")." 
                               ,'$this->q33_hora' 
                               ,'$this->q33_obs' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q33_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q33_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q33_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q33_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5129,'$this->q33_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,70,5129,'','".AddSlashes(pg_result($resaco,0,'q33_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,70,354,'','".AddSlashes(pg_result($resaco,0,'q33_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,70,8951,'','".AddSlashes(pg_result($resaco,0,'q33_tiporeg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,70,9353,'','".AddSlashes(pg_result($resaco,0,'q33_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,70,9354,'','".AddSlashes(pg_result($resaco,0,'q33_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,70,9355,'','".AddSlashes(pg_result($resaco,0,'q33_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q33_codigo=null) { 
      $this->atualizacampos();
     $sql = " update varfix set ";
     $virgula = "";
     if(trim($this->q33_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q33_codigo"])){ 
       $sql  .= $virgula." q33_codigo = $this->q33_codigo ";
       $virgula = ",";
       if(trim($this->q33_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "q33_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q33_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q33_inscr"])){ 
       $sql  .= $virgula." q33_inscr = $this->q33_inscr ";
       $virgula = ",";
       if(trim($this->q33_inscr) == null ){ 
         $this->erro_sql = " Campo inscricao nao Informado.";
         $this->erro_campo = "q33_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q33_tiporeg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q33_tiporeg"])){ 
       $sql  .= $virgula." q33_tiporeg = '$this->q33_tiporeg' ";
       $virgula = ",";
       if(trim($this->q33_tiporeg) == null ){ 
         $this->erro_sql = " Campo Tipo de regime nao Informado.";
         $this->erro_campo = "q33_tiporeg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q33_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q33_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["q33_data_dia"] !="") ){ 
       $sql  .= $virgula." q33_data = '$this->q33_data' ";
       $virgula = ",";
       if(trim($this->q33_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "q33_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["q33_data_dia"])){ 
         $sql  .= $virgula." q33_data = null ";
         $virgula = ",";
         if(trim($this->q33_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "q33_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->q33_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q33_hora"])){ 
       $sql  .= $virgula." q33_hora = '$this->q33_hora' ";
       $virgula = ",";
       if(trim($this->q33_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "q33_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q33_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q33_obs"])){ 
       $sql  .= $virgula." q33_obs = '$this->q33_obs' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q33_codigo!=null){
       $sql .= " q33_codigo = $this->q33_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q33_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5129,'$this->q33_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q33_codigo"]))
           $resac = db_query("insert into db_acount values($acount,70,5129,'".AddSlashes(pg_result($resaco,$conresaco,'q33_codigo'))."','$this->q33_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q33_inscr"]))
           $resac = db_query("insert into db_acount values($acount,70,354,'".AddSlashes(pg_result($resaco,$conresaco,'q33_inscr'))."','$this->q33_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q33_tiporeg"]))
           $resac = db_query("insert into db_acount values($acount,70,8951,'".AddSlashes(pg_result($resaco,$conresaco,'q33_tiporeg'))."','$this->q33_tiporeg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q33_data"]))
           $resac = db_query("insert into db_acount values($acount,70,9353,'".AddSlashes(pg_result($resaco,$conresaco,'q33_data'))."','$this->q33_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q33_hora"]))
           $resac = db_query("insert into db_acount values($acount,70,9354,'".AddSlashes(pg_result($resaco,$conresaco,'q33_hora'))."','$this->q33_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q33_obs"]))
           $resac = db_query("insert into db_acount values($acount,70,9355,'".AddSlashes(pg_result($resaco,$conresaco,'q33_obs'))."','$this->q33_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q33_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q33_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q33_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q33_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q33_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5129,'$q33_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,70,5129,'','".AddSlashes(pg_result($resaco,$iresaco,'q33_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,70,354,'','".AddSlashes(pg_result($resaco,$iresaco,'q33_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,70,8951,'','".AddSlashes(pg_result($resaco,$iresaco,'q33_tiporeg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,70,9353,'','".AddSlashes(pg_result($resaco,$iresaco,'q33_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,70,9354,'','".AddSlashes(pg_result($resaco,$iresaco,'q33_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,70,9355,'','".AddSlashes(pg_result($resaco,$iresaco,'q33_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from varfix
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q33_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q33_codigo = $q33_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q33_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q33_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q33_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:varfix";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q33_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from varfix ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = varfix.q33_inscr";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q33_codigo!=null ){
         $sql2 .= " where varfix.q33_codigo = $q33_codigo "; 
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
   function sql_query_file ( $q33_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from varfix ";
     $sql2 = "";
     if($dbwhere==""){
       if($q33_codigo!=null ){
         $sql2 .= " where varfix.q33_codigo = $q33_codigo "; 
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