<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: contabilidade
//CLASSE DA ENTIDADE inscricaopassivaanulada
class cl_inscricaopassivaanulada { 
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
   var $c39_sequencial = 0; 
   var $c39_inscricaopassivo = 0; 
   var $c39_db_usuarios = 0; 
   var $c39_data_dia = null; 
   var $c39_data_mes = null; 
   var $c39_data_ano = null; 
   var $c39_data = null; 
   var $c39_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c39_sequencial = int4 = Incricao Anulada 
                 c39_inscricaopassivo = int4 = Inscrição Passiva 
                 c39_db_usuarios = int4 = Usuário 
                 c39_data = date = Data da Anulação 
                 c39_observacao = text = Motivo 
                 ";
   //funcao construtor da classe 
   function cl_inscricaopassivaanulada() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("inscricaopassivaanulada"); 
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
       $this->c39_sequencial = ($this->c39_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c39_sequencial"]:$this->c39_sequencial);
       $this->c39_inscricaopassivo = ($this->c39_inscricaopassivo == ""?@$GLOBALS["HTTP_POST_VARS"]["c39_inscricaopassivo"]:$this->c39_inscricaopassivo);
       $this->c39_db_usuarios = ($this->c39_db_usuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["c39_db_usuarios"]:$this->c39_db_usuarios);
       if($this->c39_data == ""){
         $this->c39_data_dia = ($this->c39_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c39_data_dia"]:$this->c39_data_dia);
         $this->c39_data_mes = ($this->c39_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c39_data_mes"]:$this->c39_data_mes);
         $this->c39_data_ano = ($this->c39_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c39_data_ano"]:$this->c39_data_ano);
         if($this->c39_data_dia != ""){
            $this->c39_data = $this->c39_data_ano."-".$this->c39_data_mes."-".$this->c39_data_dia;
         }
       }
       $this->c39_observacao = ($this->c39_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["c39_observacao"]:$this->c39_observacao);
     }else{
       $this->c39_sequencial = ($this->c39_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c39_sequencial"]:$this->c39_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c39_sequencial){ 
      $this->atualizacampos();
     if($this->c39_inscricaopassivo == null ){ 
       $this->erro_sql = " Campo Inscrição Passiva nao Informado.";
       $this->erro_campo = "c39_inscricaopassivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c39_db_usuarios == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "c39_db_usuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c39_data == null ){ 
       $this->erro_sql = " Campo Data da Anulação nao Informado.";
       $this->erro_campo = "c39_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c39_observacao == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "c39_observacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c39_sequencial == "" || $c39_sequencial == null ){
       $result = db_query("select nextval('inscricaopassivaanulada_c39_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: inscricaopassivaanulada_c39_sequencial_seq do campo: c39_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c39_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from inscricaopassivaanulada_c39_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c39_sequencial)){
         $this->erro_sql = " Campo c39_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c39_sequencial = $c39_sequencial; 
       }
     }
     if(($this->c39_sequencial == null) || ($this->c39_sequencial == "") ){ 
       $this->erro_sql = " Campo c39_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into inscricaopassivaanulada(
                                       c39_sequencial 
                                      ,c39_inscricaopassivo 
                                      ,c39_db_usuarios 
                                      ,c39_data 
                                      ,c39_observacao 
                       )
                values (
                                $this->c39_sequencial 
                               ,$this->c39_inscricaopassivo 
                               ,$this->c39_db_usuarios 
                               ,".($this->c39_data == "null" || $this->c39_data == ""?"null":"'".$this->c39_data."'")." 
                               ,'$this->c39_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Anulação da Inscrição ($this->c39_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Anulação da Inscrição já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Anulação da Inscrição ($this->c39_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c39_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c39_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18995,'$this->c39_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3379,18995,'','".AddSlashes(pg_result($resaco,0,'c39_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3379,18996,'','".AddSlashes(pg_result($resaco,0,'c39_inscricaopassivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3379,18997,'','".AddSlashes(pg_result($resaco,0,'c39_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3379,18998,'','".AddSlashes(pg_result($resaco,0,'c39_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3379,18999,'','".AddSlashes(pg_result($resaco,0,'c39_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c39_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update inscricaopassivaanulada set ";
     $virgula = "";
     if(trim($this->c39_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c39_sequencial"])){ 
       $sql  .= $virgula." c39_sequencial = $this->c39_sequencial ";
       $virgula = ",";
       if(trim($this->c39_sequencial) == null ){ 
         $this->erro_sql = " Campo Incricao Anulada nao Informado.";
         $this->erro_campo = "c39_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c39_inscricaopassivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c39_inscricaopassivo"])){ 
       $sql  .= $virgula." c39_inscricaopassivo = $this->c39_inscricaopassivo ";
       $virgula = ",";
       if(trim($this->c39_inscricaopassivo) == null ){ 
         $this->erro_sql = " Campo Inscrição Passiva nao Informado.";
         $this->erro_campo = "c39_inscricaopassivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c39_db_usuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c39_db_usuarios"])){ 
       $sql  .= $virgula." c39_db_usuarios = $this->c39_db_usuarios ";
       $virgula = ",";
       if(trim($this->c39_db_usuarios) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "c39_db_usuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c39_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c39_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c39_data_dia"] !="") ){ 
       $sql  .= $virgula." c39_data = '$this->c39_data' ";
       $virgula = ",";
       if(trim($this->c39_data) == null ){ 
         $this->erro_sql = " Campo Data da Anulação nao Informado.";
         $this->erro_campo = "c39_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c39_data_dia"])){ 
         $sql  .= $virgula." c39_data = null ";
         $virgula = ",";
         if(trim($this->c39_data) == null ){ 
           $this->erro_sql = " Campo Data da Anulação nao Informado.";
           $this->erro_campo = "c39_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c39_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c39_observacao"])){ 
       $sql  .= $virgula." c39_observacao = '$this->c39_observacao' ";
       $virgula = ",";
       if(trim($this->c39_observacao) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "c39_observacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c39_sequencial!=null){
       $sql .= " c39_sequencial = $this->c39_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c39_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18995,'$this->c39_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c39_sequencial"]) || $this->c39_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3379,18995,'".AddSlashes(pg_result($resaco,$conresaco,'c39_sequencial'))."','$this->c39_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c39_inscricaopassivo"]) || $this->c39_inscricaopassivo != "")
           $resac = db_query("insert into db_acount values($acount,3379,18996,'".AddSlashes(pg_result($resaco,$conresaco,'c39_inscricaopassivo'))."','$this->c39_inscricaopassivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c39_db_usuarios"]) || $this->c39_db_usuarios != "")
           $resac = db_query("insert into db_acount values($acount,3379,18997,'".AddSlashes(pg_result($resaco,$conresaco,'c39_db_usuarios'))."','$this->c39_db_usuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c39_data"]) || $this->c39_data != "")
           $resac = db_query("insert into db_acount values($acount,3379,18998,'".AddSlashes(pg_result($resaco,$conresaco,'c39_data'))."','$this->c39_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c39_observacao"]) || $this->c39_observacao != "")
           $resac = db_query("insert into db_acount values($acount,3379,18999,'".AddSlashes(pg_result($resaco,$conresaco,'c39_observacao'))."','$this->c39_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anulação da Inscrição nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c39_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anulação da Inscrição nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c39_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c39_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18995,'$c39_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3379,18995,'','".AddSlashes(pg_result($resaco,$iresaco,'c39_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3379,18996,'','".AddSlashes(pg_result($resaco,$iresaco,'c39_inscricaopassivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3379,18997,'','".AddSlashes(pg_result($resaco,$iresaco,'c39_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3379,18998,'','".AddSlashes(pg_result($resaco,$iresaco,'c39_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3379,18999,'','".AddSlashes(pg_result($resaco,$iresaco,'c39_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from inscricaopassivaanulada
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c39_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c39_sequencial = $c39_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Anulação da Inscrição nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c39_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Anulação da Inscrição nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c39_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:inscricaopassivaanulada";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c39_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inscricaopassivaanulada ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inscricaopassivaanulada.c39_db_usuarios";
     $sql .= "      inner join inscricaopassivo  on  inscricaopassivo.c36_sequencial = inscricaopassivaanulada.c39_inscricaopassivo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = inscricaopassivo.c36_cgm";
     $sql .= "      inner join db_config  on  db_config.codigo = inscricaopassivo.c36_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = inscricaopassivo.c36_db_usuarios";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = inscricaopassivo.c36_codele and  orcelemento.o56_anousu = inscricaopassivo.c36_anousu";
     $sql .= "      inner join conhist  on  conhist.c50_codhist = inscricaopassivo.c36_conhist";
     $sql2 = "";
     if($dbwhere==""){
       if($c39_sequencial!=null ){
         $sql2 .= " where inscricaopassivaanulada.c39_sequencial = $c39_sequencial "; 
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
   function sql_query_file ( $c39_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from inscricaopassivaanulada ";
     $sql2 = "";
     if($dbwhere==""){
       if($c39_sequencial!=null ){
         $sql2 .= " where inscricaopassivaanulada.c39_sequencial = $c39_sequencial "; 
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