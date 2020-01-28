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

//MODULO: licitação
//CLASSE DA ENTIDADE liccomissao
class cl_liccomissao { 
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
   var $l30_codigo = 0; 
   var $l30_data_dia = null; 
   var $l30_data_mes = null; 
   var $l30_data_ano = null; 
   var $l30_data = null; 
   var $l30_portaria = null; 
   var $l30_datavalid_dia = null; 
   var $l30_datavalid_mes = null; 
   var $l30_datavalid_ano = null; 
   var $l30_datavalid = null; 
   var $l30_tipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l30_codigo = int4 = Código Sequencial 
                 l30_data = date = Data 
                 l30_portaria = varchar(20) = Portaria 
                 l30_datavalid = date = Validade 
                 l30_tipo = int4 = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_liccomissao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liccomissao"); 
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
       $this->l30_codigo = ($this->l30_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l30_codigo"]:$this->l30_codigo);
       if($this->l30_data == ""){
         $this->l30_data_dia = ($this->l30_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l30_data_dia"]:$this->l30_data_dia);
         $this->l30_data_mes = ($this->l30_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l30_data_mes"]:$this->l30_data_mes);
         $this->l30_data_ano = ($this->l30_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l30_data_ano"]:$this->l30_data_ano);
         if($this->l30_data_dia != ""){
            $this->l30_data = $this->l30_data_ano."-".$this->l30_data_mes."-".$this->l30_data_dia;
         }
       }
       $this->l30_portaria = ($this->l30_portaria == ""?@$GLOBALS["HTTP_POST_VARS"]["l30_portaria"]:$this->l30_portaria);
       if($this->l30_datavalid == ""){
         $this->l30_datavalid_dia = ($this->l30_datavalid_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l30_datavalid_dia"]:$this->l30_datavalid_dia);
         $this->l30_datavalid_mes = ($this->l30_datavalid_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l30_datavalid_mes"]:$this->l30_datavalid_mes);
         $this->l30_datavalid_ano = ($this->l30_datavalid_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l30_datavalid_ano"]:$this->l30_datavalid_ano);
         if($this->l30_datavalid_dia != ""){
            $this->l30_datavalid = $this->l30_datavalid_ano."-".$this->l30_datavalid_mes."-".$this->l30_datavalid_dia;
         }
       }
       $this->l30_tipo = ($this->l30_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["l30_tipo"]:$this->l30_tipo);
     }else{
       $this->l30_codigo = ($this->l30_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l30_codigo"]:$this->l30_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($l30_codigo){ 
      $this->atualizacampos();
     if($this->l30_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "l30_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l30_portaria == null ){ 
       $this->erro_sql = " Campo Portaria nao Informado.";
       $this->erro_campo = "l30_portaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l30_datavalid == null ){ 
       $this->erro_sql = " Campo Validade nao Informado.";
       $this->erro_campo = "l30_datavalid_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l30_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "l30_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l30_codigo == "" || $l30_codigo == null ){
       $result = db_query("select nextval('liccomissao_l30_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liccomissao_l30_codigo_seq do campo: l30_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l30_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liccomissao_l30_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $l30_codigo)){
         $this->erro_sql = " Campo l30_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l30_codigo = $l30_codigo; 
       }
     }
     if(($this->l30_codigo == null) || ($this->l30_codigo == "") ){ 
       $this->erro_sql = " Campo l30_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liccomissao(
                                       l30_codigo 
                                      ,l30_data 
                                      ,l30_portaria 
                                      ,l30_datavalid 
                                      ,l30_tipo 
                       )
                values (
                                $this->l30_codigo 
                               ,".($this->l30_data == "null" || $this->l30_data == ""?"null":"'".$this->l30_data."'")." 
                               ,'$this->l30_portaria' 
                               ,".($this->l30_datavalid == "null" || $this->l30_datavalid == ""?"null":"'".$this->l30_datavalid."'")." 
                               ,$this->l30_tipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Comissão da Licitação ($this->l30_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Comissão da Licitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Comissão da Licitação ($this->l30_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l30_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l30_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7902,'$this->l30_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1324,7902,'','".AddSlashes(pg_result($resaco,0,'l30_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1324,7903,'','".AddSlashes(pg_result($resaco,0,'l30_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1324,7904,'','".AddSlashes(pg_result($resaco,0,'l30_portaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1324,7916,'','".AddSlashes(pg_result($resaco,0,'l30_datavalid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1324,7915,'','".AddSlashes(pg_result($resaco,0,'l30_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l30_codigo=null) { 
      $this->atualizacampos();
     $sql = " update liccomissao set ";
     $virgula = "";
     if(trim($this->l30_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l30_codigo"])){ 
       $sql  .= $virgula." l30_codigo = $this->l30_codigo ";
       $virgula = ",";
       if(trim($this->l30_codigo) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "l30_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l30_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l30_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l30_data_dia"] !="") ){ 
       $sql  .= $virgula." l30_data = '$this->l30_data' ";
       $virgula = ",";
       if(trim($this->l30_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "l30_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l30_data_dia"])){ 
         $sql  .= $virgula." l30_data = null ";
         $virgula = ",";
         if(trim($this->l30_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "l30_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l30_portaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l30_portaria"])){ 
       $sql  .= $virgula." l30_portaria = '$this->l30_portaria' ";
       $virgula = ",";
       if(trim($this->l30_portaria) == null ){ 
         $this->erro_sql = " Campo Portaria nao Informado.";
         $this->erro_campo = "l30_portaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l30_datavalid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l30_datavalid_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l30_datavalid_dia"] !="") ){ 
       $sql  .= $virgula." l30_datavalid = '$this->l30_datavalid' ";
       $virgula = ",";
       if(trim($this->l30_datavalid) == null ){ 
         $this->erro_sql = " Campo Validade nao Informado.";
         $this->erro_campo = "l30_datavalid_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l30_datavalid_dia"])){ 
         $sql  .= $virgula." l30_datavalid = null ";
         $virgula = ",";
         if(trim($this->l30_datavalid) == null ){ 
           $this->erro_sql = " Campo Validade nao Informado.";
           $this->erro_campo = "l30_datavalid_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l30_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l30_tipo"])){ 
       $sql  .= $virgula." l30_tipo = $this->l30_tipo ";
       $virgula = ",";
       if(trim($this->l30_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "l30_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l30_codigo!=null){
       $sql .= " l30_codigo = $this->l30_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l30_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7902,'$this->l30_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l30_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1324,7902,'".AddSlashes(pg_result($resaco,$conresaco,'l30_codigo'))."','$this->l30_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l30_data"]))
           $resac = db_query("insert into db_acount values($acount,1324,7903,'".AddSlashes(pg_result($resaco,$conresaco,'l30_data'))."','$this->l30_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l30_portaria"]))
           $resac = db_query("insert into db_acount values($acount,1324,7904,'".AddSlashes(pg_result($resaco,$conresaco,'l30_portaria'))."','$this->l30_portaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l30_datavalid"]))
           $resac = db_query("insert into db_acount values($acount,1324,7916,'".AddSlashes(pg_result($resaco,$conresaco,'l30_datavalid'))."','$this->l30_datavalid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l30_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1324,7915,'".AddSlashes(pg_result($resaco,$conresaco,'l30_tipo'))."','$this->l30_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Comissão da Licitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l30_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Comissão da Licitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l30_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l30_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l30_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l30_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7902,'$l30_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1324,7902,'','".AddSlashes(pg_result($resaco,$iresaco,'l30_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1324,7903,'','".AddSlashes(pg_result($resaco,$iresaco,'l30_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1324,7904,'','".AddSlashes(pg_result($resaco,$iresaco,'l30_portaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1324,7916,'','".AddSlashes(pg_result($resaco,$iresaco,'l30_datavalid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1324,7915,'','".AddSlashes(pg_result($resaco,$iresaco,'l30_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liccomissao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l30_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l30_codigo = $l30_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Comissão da Licitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l30_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Comissão da Licitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l30_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l30_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:liccomissao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $l30_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liccomissao ";
     $sql2 = "";
     if($dbwhere==""){
       if($l30_codigo!=null ){
         $sql2 .= " where liccomissao.l30_codigo = $l30_codigo "; 
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
   function sql_query_file ( $l30_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liccomissao ";
     $sql2 = "";
     if($dbwhere==""){
       if($l30_codigo!=null ){
         $sql2 .= " where liccomissao.l30_codigo = $l30_codigo "; 
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