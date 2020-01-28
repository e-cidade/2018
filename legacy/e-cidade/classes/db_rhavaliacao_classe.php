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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE rhavaliacao
class cl_rhavaliacao { 
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
   var $h73_sequencial = 0; 
   var $h73_rhpromocao = 0; 
   var $h73_dtavaliacao_dia = null; 
   var $h73_dtavaliacao_mes = null; 
   var $h73_dtavaliacao_ano = null; 
   var $h73_dtavaliacao = null; 
   var $h73_dtinclusao_dia = null; 
   var $h73_dtinclusao_mes = null; 
   var $h73_dtinclusao_ano = null; 
   var $h73_dtinclusao = null; 
   var $h73_usuario = 0; 
   var $h73_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h73_sequencial = int4 = sequencial 
                 h73_rhpromocao = int4 = Promoção 
                 h73_dtavaliacao = date = Data de avaliação 
                 h73_dtinclusao = date = Data de inclusão 
                 h73_usuario = int4 = Usuário 
                 h73_observacao = text = Observação 
                 ";
   //funcao construtor da classe 
   function cl_rhavaliacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhavaliacao"); 
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
       $this->h73_sequencial = ($this->h73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h73_sequencial"]:$this->h73_sequencial);
       $this->h73_rhpromocao = ($this->h73_rhpromocao == ""?@$GLOBALS["HTTP_POST_VARS"]["h73_rhpromocao"]:$this->h73_rhpromocao);
       if($this->h73_dtavaliacao == ""){
         $this->h73_dtavaliacao_dia = ($this->h73_dtavaliacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h73_dtavaliacao_dia"]:$this->h73_dtavaliacao_dia);
         $this->h73_dtavaliacao_mes = ($this->h73_dtavaliacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h73_dtavaliacao_mes"]:$this->h73_dtavaliacao_mes);
         $this->h73_dtavaliacao_ano = ($this->h73_dtavaliacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h73_dtavaliacao_ano"]:$this->h73_dtavaliacao_ano);
         if($this->h73_dtavaliacao_dia != ""){
            $this->h73_dtavaliacao = $this->h73_dtavaliacao_ano."-".$this->h73_dtavaliacao_mes."-".$this->h73_dtavaliacao_dia;
         }
       }
       if($this->h73_dtinclusao == ""){
         $this->h73_dtinclusao_dia = ($this->h73_dtinclusao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h73_dtinclusao_dia"]:$this->h73_dtinclusao_dia);
         $this->h73_dtinclusao_mes = ($this->h73_dtinclusao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h73_dtinclusao_mes"]:$this->h73_dtinclusao_mes);
         $this->h73_dtinclusao_ano = ($this->h73_dtinclusao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h73_dtinclusao_ano"]:$this->h73_dtinclusao_ano);
         if($this->h73_dtinclusao_dia != ""){
            $this->h73_dtinclusao = $this->h73_dtinclusao_ano."-".$this->h73_dtinclusao_mes."-".$this->h73_dtinclusao_dia;
         }
       }
       $this->h73_usuario = ($this->h73_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["h73_usuario"]:$this->h73_usuario);
       $this->h73_observacao = ($this->h73_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["h73_observacao"]:$this->h73_observacao);
     }else{
       $this->h73_sequencial = ($this->h73_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h73_sequencial"]:$this->h73_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h73_sequencial){ 
      $this->atualizacampos();
     if($this->h73_rhpromocao == null ){ 
       $this->erro_sql = " Campo Promoção nao Informado.";
       $this->erro_campo = "h73_rhpromocao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h73_dtavaliacao == null ){ 
       $this->erro_sql = " Campo Data de avaliação nao Informado.";
       $this->erro_campo = "h73_dtavaliacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h73_dtinclusao == null ){ 
       $this->erro_sql = " Campo Data de inclusão nao Informado.";
       $this->erro_campo = "h73_dtinclusao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h73_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "h73_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h73_sequencial == "" || $h73_sequencial == null ){
       $result = db_query("select nextval('rhavaliacao_h73_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhavaliacao_h73_sequencial_seq do campo: h73_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h73_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhavaliacao_h73_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h73_sequencial)){
         $this->erro_sql = " Campo h73_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h73_sequencial = $h73_sequencial; 
       }
     }
     if(($this->h73_sequencial == null) || ($this->h73_sequencial == "") ){ 
       $this->erro_sql = " Campo h73_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhavaliacao(
                                       h73_sequencial 
                                      ,h73_rhpromocao 
                                      ,h73_dtavaliacao 
                                      ,h73_dtinclusao 
                                      ,h73_usuario 
                                      ,h73_observacao 
                       )
                values (
                                $this->h73_sequencial 
                               ,$this->h73_rhpromocao 
                               ,".($this->h73_dtavaliacao == "null" || $this->h73_dtavaliacao == ""?"null":"'".$this->h73_dtavaliacao."'")." 
                               ,".($this->h73_dtinclusao == "null" || $this->h73_dtinclusao == ""?"null":"'".$this->h73_dtinclusao."'")." 
                               ,$this->h73_usuario 
                               ,'$this->h73_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliação ($this->h73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliação ($this->h73_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h73_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h73_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18715,'$this->h73_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3316,18715,'','".AddSlashes(pg_result($resaco,0,'h73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3316,18716,'','".AddSlashes(pg_result($resaco,0,'h73_rhpromocao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3316,18717,'','".AddSlashes(pg_result($resaco,0,'h73_dtavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3316,18718,'','".AddSlashes(pg_result($resaco,0,'h73_dtinclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3316,18719,'','".AddSlashes(pg_result($resaco,0,'h73_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3316,18720,'','".AddSlashes(pg_result($resaco,0,'h73_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h73_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhavaliacao set ";
     $virgula = "";
     if(trim($this->h73_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h73_sequencial"])){ 
       $sql  .= $virgula." h73_sequencial = $this->h73_sequencial ";
       $virgula = ",";
       if(trim($this->h73_sequencial) == null ){ 
         $this->erro_sql = " Campo sequencial nao Informado.";
         $this->erro_campo = "h73_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h73_rhpromocao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h73_rhpromocao"])){ 
       $sql  .= $virgula." h73_rhpromocao = $this->h73_rhpromocao ";
       $virgula = ",";
       if(trim($this->h73_rhpromocao) == null ){ 
         $this->erro_sql = " Campo Promoção nao Informado.";
         $this->erro_campo = "h73_rhpromocao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h73_dtavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h73_dtavaliacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h73_dtavaliacao_dia"] !="") ){ 
       $sql  .= $virgula." h73_dtavaliacao = '$this->h73_dtavaliacao' ";
       $virgula = ",";
       if(trim($this->h73_dtavaliacao) == null ){ 
         $this->erro_sql = " Campo Data de avaliação nao Informado.";
         $this->erro_campo = "h73_dtavaliacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h73_dtavaliacao_dia"])){ 
         $sql  .= $virgula." h73_dtavaliacao = null ";
         $virgula = ",";
         if(trim($this->h73_dtavaliacao) == null ){ 
           $this->erro_sql = " Campo Data de avaliação nao Informado.";
           $this->erro_campo = "h73_dtavaliacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h73_dtinclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h73_dtinclusao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h73_dtinclusao_dia"] !="") ){ 
       $sql  .= $virgula." h73_dtinclusao = '$this->h73_dtinclusao' ";
       $virgula = ",";
       if(trim($this->h73_dtinclusao) == null ){ 
         $this->erro_sql = " Campo Data de inclusão nao Informado.";
         $this->erro_campo = "h73_dtinclusao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h73_dtinclusao_dia"])){ 
         $sql  .= $virgula." h73_dtinclusao = null ";
         $virgula = ",";
         if(trim($this->h73_dtinclusao) == null ){ 
           $this->erro_sql = " Campo Data de inclusão nao Informado.";
           $this->erro_campo = "h73_dtinclusao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h73_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h73_usuario"])){ 
       $sql  .= $virgula." h73_usuario = $this->h73_usuario ";
       $virgula = ",";
       if(trim($this->h73_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "h73_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h73_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h73_observacao"])){ 
       $sql  .= $virgula." h73_observacao = '$this->h73_observacao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($h73_sequencial!=null){
       $sql .= " h73_sequencial = $this->h73_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h73_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18715,'$this->h73_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h73_sequencial"]) || $this->h73_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3316,18715,'".AddSlashes(pg_result($resaco,$conresaco,'h73_sequencial'))."','$this->h73_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h73_rhpromocao"]) || $this->h73_rhpromocao != "")
           $resac = db_query("insert into db_acount values($acount,3316,18716,'".AddSlashes(pg_result($resaco,$conresaco,'h73_rhpromocao'))."','$this->h73_rhpromocao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h73_dtavaliacao"]) || $this->h73_dtavaliacao != "")
           $resac = db_query("insert into db_acount values($acount,3316,18717,'".AddSlashes(pg_result($resaco,$conresaco,'h73_dtavaliacao'))."','$this->h73_dtavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h73_dtinclusao"]) || $this->h73_dtinclusao != "")
           $resac = db_query("insert into db_acount values($acount,3316,18718,'".AddSlashes(pg_result($resaco,$conresaco,'h73_dtinclusao'))."','$this->h73_dtinclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h73_usuario"]) || $this->h73_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3316,18719,'".AddSlashes(pg_result($resaco,$conresaco,'h73_usuario'))."','$this->h73_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h73_observacao"]) || $this->h73_observacao != "")
           $resac = db_query("insert into db_acount values($acount,3316,18720,'".AddSlashes(pg_result($resaco,$conresaco,'h73_observacao'))."','$this->h73_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h73_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h73_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18715,'$h73_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3316,18715,'','".AddSlashes(pg_result($resaco,$iresaco,'h73_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3316,18716,'','".AddSlashes(pg_result($resaco,$iresaco,'h73_rhpromocao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3316,18717,'','".AddSlashes(pg_result($resaco,$iresaco,'h73_dtavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3316,18718,'','".AddSlashes(pg_result($resaco,$iresaco,'h73_dtinclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3316,18719,'','".AddSlashes(pg_result($resaco,$iresaco,'h73_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3316,18720,'','".AddSlashes(pg_result($resaco,$iresaco,'h73_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhavaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h73_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h73_sequencial = $h73_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h73_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h73_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h73_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhavaliacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $h73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhavaliacao ";
     $sql .= "      inner join rhpromocao  on  rhpromocao.h72_sequencial = rhavaliacao.h73_rhpromocao";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhpromocao.h72_regist";
     $sql2 = "";
     if($dbwhere==""){
       if($h73_sequencial!=null ){
         $sql2 .= " where rhavaliacao.h73_sequencial = $h73_sequencial "; 
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
   function sql_query_file ( $h73_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($h73_sequencial!=null ){
         $sql2 .= " where rhavaliacao.h73_sequencial = $h73_sequencial "; 
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