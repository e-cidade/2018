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
//CLASSE DA ENTIDADE liclicitemanu
class cl_liclicitemanu { 
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
   var $l07_codigo = 0; 
   var $l07_usuario = 0; 
   var $l07_data_dia = null; 
   var $l07_data_mes = null; 
   var $l07_data_ano = null; 
   var $l07_data = null; 
   var $l07_hora = null; 
   var $l07_motivo = null; 
   var $l07_liclicitem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l07_codigo = int8 = Cód. Sequencial 
                 l07_usuario = int4 = Usuário 
                 l07_data = date = Data 
                 l07_hora = char(5) = Hora 
                 l07_motivo = text = Motivo 
                 l07_liclicitem = int8 = Item da licitação 
                 ";
   //funcao construtor da classe 
   function cl_liclicitemanu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("liclicitemanu"); 
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
       $this->l07_codigo = ($this->l07_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l07_codigo"]:$this->l07_codigo);
       $this->l07_usuario = ($this->l07_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["l07_usuario"]:$this->l07_usuario);
       if($this->l07_data == ""){
         $this->l07_data_dia = ($this->l07_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l07_data_dia"]:$this->l07_data_dia);
         $this->l07_data_mes = ($this->l07_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l07_data_mes"]:$this->l07_data_mes);
         $this->l07_data_ano = ($this->l07_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l07_data_ano"]:$this->l07_data_ano);
         if($this->l07_data_dia != ""){
            $this->l07_data = $this->l07_data_ano."-".$this->l07_data_mes."-".$this->l07_data_dia;
         }
       }
       $this->l07_hora = ($this->l07_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["l07_hora"]:$this->l07_hora);
       $this->l07_motivo = ($this->l07_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["l07_motivo"]:$this->l07_motivo);
       $this->l07_liclicitem = ($this->l07_liclicitem == ""?@$GLOBALS["HTTP_POST_VARS"]["l07_liclicitem"]:$this->l07_liclicitem);
     }else{
       $this->l07_codigo = ($this->l07_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["l07_codigo"]:$this->l07_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($l07_codigo){ 
      $this->atualizacampos();
     if($this->l07_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "l07_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l07_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "l07_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l07_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "l07_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l07_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "l07_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l07_liclicitem == null ){ 
       $this->erro_sql = " Campo Item da licitação nao Informado.";
       $this->erro_campo = "l07_liclicitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l07_codigo == "" || $l07_codigo == null ){
       $result = db_query("select nextval('liclicitemanu_l07_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitemanu_l07_codigo_seq do campo: l07_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l07_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from liclicitemanu_l07_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $l07_codigo)){
         $this->erro_sql = " Campo l07_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l07_codigo = $l07_codigo; 
       }
     }
     if(($this->l07_codigo == null) || ($this->l07_codigo == "") ){ 
       $this->erro_sql = " Campo l07_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into liclicitemanu(
                                       l07_codigo 
                                      ,l07_usuario 
                                      ,l07_data 
                                      ,l07_hora 
                                      ,l07_motivo 
                                      ,l07_liclicitem 
                       )
                values (
                                $this->l07_codigo 
                               ,$this->l07_usuario 
                               ,".($this->l07_data == "null" || $this->l07_data == ""?"null":"'".$this->l07_data."'")." 
                               ,'$this->l07_hora' 
                               ,'$this->l07_motivo' 
                               ,$this->l07_liclicitem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens de licitação anulada ($this->l07_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens de licitação anulada já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens de licitação anulada ($this->l07_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l07_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l07_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10015,'$this->l07_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1720,10015,'','".AddSlashes(pg_result($resaco,0,'l07_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1720,10016,'','".AddSlashes(pg_result($resaco,0,'l07_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1720,10017,'','".AddSlashes(pg_result($resaco,0,'l07_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1720,10018,'','".AddSlashes(pg_result($resaco,0,'l07_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1720,10019,'','".AddSlashes(pg_result($resaco,0,'l07_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1720,10101,'','".AddSlashes(pg_result($resaco,0,'l07_liclicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l07_codigo=null) { 
      $this->atualizacampos();
     $sql = " update liclicitemanu set ";
     $virgula = "";
     if(trim($this->l07_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l07_codigo"])){ 
       $sql  .= $virgula." l07_codigo = $this->l07_codigo ";
       $virgula = ",";
       if(trim($this->l07_codigo) == null ){ 
         $this->erro_sql = " Campo Cód. Sequencial nao Informado.";
         $this->erro_campo = "l07_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l07_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l07_usuario"])){ 
       $sql  .= $virgula." l07_usuario = $this->l07_usuario ";
       $virgula = ",";
       if(trim($this->l07_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "l07_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l07_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l07_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l07_data_dia"] !="") ){ 
       $sql  .= $virgula." l07_data = '$this->l07_data' ";
       $virgula = ",";
       if(trim($this->l07_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "l07_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l07_data_dia"])){ 
         $sql  .= $virgula." l07_data = null ";
         $virgula = ",";
         if(trim($this->l07_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "l07_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l07_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l07_hora"])){ 
       $sql  .= $virgula." l07_hora = '$this->l07_hora' ";
       $virgula = ",";
       if(trim($this->l07_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "l07_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l07_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l07_motivo"])){ 
       $sql  .= $virgula." l07_motivo = '$this->l07_motivo' ";
       $virgula = ",";
       if(trim($this->l07_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "l07_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l07_liclicitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l07_liclicitem"])){ 
       $sql  .= $virgula." l07_liclicitem = $this->l07_liclicitem ";
       $virgula = ",";
       if(trim($this->l07_liclicitem) == null ){ 
         $this->erro_sql = " Campo Item da licitação nao Informado.";
         $this->erro_campo = "l07_liclicitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($l07_codigo!=null){
       $sql .= " l07_codigo = $this->l07_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l07_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10015,'$this->l07_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l07_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1720,10015,'".AddSlashes(pg_result($resaco,$conresaco,'l07_codigo'))."','$this->l07_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l07_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1720,10016,'".AddSlashes(pg_result($resaco,$conresaco,'l07_usuario'))."','$this->l07_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l07_data"]))
           $resac = db_query("insert into db_acount values($acount,1720,10017,'".AddSlashes(pg_result($resaco,$conresaco,'l07_data'))."','$this->l07_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l07_hora"]))
           $resac = db_query("insert into db_acount values($acount,1720,10018,'".AddSlashes(pg_result($resaco,$conresaco,'l07_hora'))."','$this->l07_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l07_motivo"]))
           $resac = db_query("insert into db_acount values($acount,1720,10019,'".AddSlashes(pg_result($resaco,$conresaco,'l07_motivo'))."','$this->l07_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l07_liclicitem"]))
           $resac = db_query("insert into db_acount values($acount,1720,10101,'".AddSlashes(pg_result($resaco,$conresaco,'l07_liclicitem'))."','$this->l07_liclicitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens de licitação anulada nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l07_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens de licitação anulada nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l07_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l07_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l07_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l07_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10015,'$l07_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1720,10015,'','".AddSlashes(pg_result($resaco,$iresaco,'l07_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1720,10016,'','".AddSlashes(pg_result($resaco,$iresaco,'l07_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1720,10017,'','".AddSlashes(pg_result($resaco,$iresaco,'l07_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1720,10018,'','".AddSlashes(pg_result($resaco,$iresaco,'l07_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1720,10019,'','".AddSlashes(pg_result($resaco,$iresaco,'l07_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1720,10101,'','".AddSlashes(pg_result($resaco,$iresaco,'l07_liclicitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from liclicitemanu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l07_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l07_codigo = $l07_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens de licitação anulada nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l07_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens de licitação anulada nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l07_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l07_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitemanu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $l07_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitemanu ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicitemanu.l07_usuario";
     $sql .= "      inner join liclicitem  on  liclicitem.l21_codigo = liclicitemanu.l07_liclicitem";
     $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitem.l21_codliclicita";
     $sql2 = "";
     if($dbwhere==""){
       if($l07_codigo!=null ){
         $sql2 .= " where liclicitemanu.l07_codigo = $l07_codigo "; 
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
   function sql_query_anu ( $l07_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitemanu ";
     $sql .= "      left  join liclicitemlote   on liclicitemlote.l04_liclicitem   = liclicitemanu.l07_liclicitem";
     $sql .= "      inner join db_usuarios      on db_usuarios.id_usuario          = liclicitemanu.l07_usuario";
     $sql .= "      inner join liclicitem       on liclicitem.l21_codigo           = liclicitemanu.l07_liclicitem";
     $sql .= "      inner join pcprocitem       on pcprocitem.pc81_codprocitem     = liclicitem.l21_codpcprocitem";
     $sql .= "      inner join solicitem        on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem";
     $sql .= "      inner join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      inner join pcmater          on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater";
     $sql .= "      inner join liclicita        on liclicita.l20_codigo            = liclicitem.l21_codliclicita";
     $sql2 = "";
     if($dbwhere==""){
       if($l07_codigo!=null ){
         $sql2 .= " where liclicitemanu.l07_codigo = $l07_codigo "; 
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
   function sql_query_file ( $l07_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from liclicitemanu ";
     $sql2 = "";
     if($dbwhere==""){
       if($l07_codigo!=null ){
         $sql2 .= " where liclicitemanu.l07_codigo = $l07_codigo "; 
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