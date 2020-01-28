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

//MODULO: compras
//CLASSE DA ENTIDADE matordemanul
class cl_matordemanul { 
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
   var $m37_sequencial = 0; 
   var $m37_hora = null; 
   var $m37_data_dia = null; 
   var $m37_data_mes = null; 
   var $m37_data_ano = null; 
   var $m37_data = null; 
   var $m37_usuario = 0; 
   var $m37_empanul = 0; 
   var $m37_tipo = 0; 
   var $m37_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m37_sequencial = int4 = Código da Anulação 
                 m37_hora = char(5) = Hora 
                 m37_data = date = Data da Anulação 
                 m37_usuario = int4 = Usuário 
                 m37_empanul = int4 = Solicitacao de Anulação 
                 m37_tipo = int4 = Tipo da Anulação 
                 m37_motivo = text = Motivo da Anulação 
                 ";
   //funcao construtor da classe 
   function cl_matordemanul() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matordemanul"); 
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
       $this->m37_sequencial = ($this->m37_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m37_sequencial"]:$this->m37_sequencial);
       $this->m37_hora = ($this->m37_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["m37_hora"]:$this->m37_hora);
       if($this->m37_data == ""){
         $this->m37_data_dia = ($this->m37_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["m37_data_dia"]:$this->m37_data_dia);
         $this->m37_data_mes = ($this->m37_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["m37_data_mes"]:$this->m37_data_mes);
         $this->m37_data_ano = ($this->m37_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["m37_data_ano"]:$this->m37_data_ano);
         if($this->m37_data_dia != ""){
            $this->m37_data = $this->m37_data_ano."-".$this->m37_data_mes."-".$this->m37_data_dia;
         }
       }
       $this->m37_usuario = ($this->m37_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["m37_usuario"]:$this->m37_usuario);
       $this->m37_empanul = ($this->m37_empanul == ""?@$GLOBALS["HTTP_POST_VARS"]["m37_empanul"]:$this->m37_empanul);
       $this->m37_tipo = ($this->m37_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["m37_tipo"]:$this->m37_tipo);
       $this->m37_motivo = ($this->m37_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["m37_motivo"]:$this->m37_motivo);
     }else{
       $this->m37_sequencial = ($this->m37_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m37_sequencial"]:$this->m37_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m37_sequencial){ 
      $this->atualizacampos();
     if($this->m37_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "m37_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m37_data == null ){ 
       $this->erro_sql = " Campo Data da Anulação nao Informado.";
       $this->erro_campo = "m37_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m37_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "m37_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m37_empanul == null ){ 
       $this->erro_sql = " Campo Solicitacao de Anulação nao Informado.";
       $this->erro_campo = "m37_empanul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m37_tipo == null ){ 
       $this->erro_sql = " Campo Tipo da Anulação nao Informado.";
       $this->erro_campo = "m37_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m37_sequencial == "" || $m37_sequencial == null ){
       $result = db_query("select nextval('matordemanul_m37_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matordemanul_m37_sequencial_seq do campo: m37_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m37_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matordemanul_m37_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m37_sequencial)){
         $this->erro_sql = " Campo m37_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m37_sequencial = $m37_sequencial; 
       }
     }
     if(($this->m37_sequencial == null) || ($this->m37_sequencial == "") ){ 
       $this->erro_sql = " Campo m37_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matordemanul(
                                       m37_sequencial 
                                      ,m37_hora 
                                      ,m37_data 
                                      ,m37_usuario 
                                      ,m37_empanul 
                                      ,m37_tipo 
                                      ,m37_motivo 
                       )
                values (
                                $this->m37_sequencial 
                               ,'$this->m37_hora' 
                               ,".($this->m37_data == "null" || $this->m37_data == ""?"null":"'".$this->m37_data."'")." 
                               ,$this->m37_usuario 
                               ,$this->m37_empanul 
                               ,$this->m37_tipo 
                               ,'$this->m37_motivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Anulação de Ordem de Compra ($this->m37_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Anulação de Ordem de Compra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Anulação de Ordem de Compra ($this->m37_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m37_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m37_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10974,'$this->m37_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1893,10974,'','".AddSlashes(pg_result($resaco,0,'m37_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1893,10975,'','".AddSlashes(pg_result($resaco,0,'m37_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1893,10976,'','".AddSlashes(pg_result($resaco,0,'m37_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1893,10977,'','".AddSlashes(pg_result($resaco,0,'m37_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1893,10978,'','".AddSlashes(pg_result($resaco,0,'m37_empanul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1893,10979,'','".AddSlashes(pg_result($resaco,0,'m37_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1893,10980,'','".AddSlashes(pg_result($resaco,0,'m37_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m37_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update matordemanul set ";
     $virgula = "";
     if(trim($this->m37_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m37_sequencial"])){ 
       $sql  .= $virgula." m37_sequencial = $this->m37_sequencial ";
       $virgula = ",";
       if(trim($this->m37_sequencial) == null ){ 
         $this->erro_sql = " Campo Código da Anulação nao Informado.";
         $this->erro_campo = "m37_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m37_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m37_hora"])){ 
       $sql  .= $virgula." m37_hora = '$this->m37_hora' ";
       $virgula = ",";
       if(trim($this->m37_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "m37_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m37_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m37_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["m37_data_dia"] !="") ){ 
       $sql  .= $virgula." m37_data = '$this->m37_data' ";
       $virgula = ",";
       if(trim($this->m37_data) == null ){ 
         $this->erro_sql = " Campo Data da Anulação nao Informado.";
         $this->erro_campo = "m37_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["m37_data_dia"])){ 
         $sql  .= $virgula." m37_data = null ";
         $virgula = ",";
         if(trim($this->m37_data) == null ){ 
           $this->erro_sql = " Campo Data da Anulação nao Informado.";
           $this->erro_campo = "m37_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->m37_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m37_usuario"])){ 
       $sql  .= $virgula." m37_usuario = $this->m37_usuario ";
       $virgula = ",";
       if(trim($this->m37_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "m37_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m37_empanul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m37_empanul"])){ 
       $sql  .= $virgula." m37_empanul = $this->m37_empanul ";
       $virgula = ",";
       if(trim($this->m37_empanul) == null ){ 
         $this->erro_sql = " Campo Solicitacao de Anulação nao Informado.";
         $this->erro_campo = "m37_empanul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m37_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m37_tipo"])){ 
       $sql  .= $virgula." m37_tipo = $this->m37_tipo ";
       $virgula = ",";
       if(trim($this->m37_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo da Anulação nao Informado.";
         $this->erro_campo = "m37_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m37_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m37_motivo"])){ 
       $sql  .= $virgula." m37_motivo = '$this->m37_motivo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($m37_sequencial!=null){
       $sql .= " m37_sequencial = $this->m37_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m37_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10974,'$this->m37_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m37_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1893,10974,'".AddSlashes(pg_result($resaco,$conresaco,'m37_sequencial'))."','$this->m37_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m37_hora"]))
           $resac = db_query("insert into db_acount values($acount,1893,10975,'".AddSlashes(pg_result($resaco,$conresaco,'m37_hora'))."','$this->m37_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m37_data"]))
           $resac = db_query("insert into db_acount values($acount,1893,10976,'".AddSlashes(pg_result($resaco,$conresaco,'m37_data'))."','$this->m37_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m37_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1893,10977,'".AddSlashes(pg_result($resaco,$conresaco,'m37_usuario'))."','$this->m37_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m37_empanul"]))
           $resac = db_query("insert into db_acount values($acount,1893,10978,'".AddSlashes(pg_result($resaco,$conresaco,'m37_empanul'))."','$this->m37_empanul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m37_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1893,10979,'".AddSlashes(pg_result($resaco,$conresaco,'m37_tipo'))."','$this->m37_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m37_motivo"]))
           $resac = db_query("insert into db_acount values($acount,1893,10980,'".AddSlashes(pg_result($resaco,$conresaco,'m37_motivo'))."','$this->m37_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anulação de Ordem de Compra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anulação de Ordem de Compra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m37_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m37_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10974,'$m37_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1893,10974,'','".AddSlashes(pg_result($resaco,$iresaco,'m37_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1893,10975,'','".AddSlashes(pg_result($resaco,$iresaco,'m37_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1893,10976,'','".AddSlashes(pg_result($resaco,$iresaco,'m37_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1893,10977,'','".AddSlashes(pg_result($resaco,$iresaco,'m37_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1893,10978,'','".AddSlashes(pg_result($resaco,$iresaco,'m37_empanul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1893,10979,'','".AddSlashes(pg_result($resaco,$iresaco,'m37_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1893,10980,'','".AddSlashes(pg_result($resaco,$iresaco,'m37_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matordemanul
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m37_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m37_sequencial = $m37_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anulação de Ordem de Compra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anulação de Ordem de Compra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m37_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matordemanul";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m37_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordemanul ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matordemanul.m37_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($m37_sequencial!=null ){
         $sql2 .= " where matordemanul.m37_sequencial = $m37_sequencial "; 
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
   function sql_query_file ( $m37_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matordemanul ";
     $sql2 = "";
     if($dbwhere==""){
       if($m37_sequencial!=null ){
         $sql2 .= " where matordemanul.m37_sequencial = $m37_sequencial "; 
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