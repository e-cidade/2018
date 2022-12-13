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

//MODULO: contabilidade
//CLASSE DA ENTIDADE contranslan
class cl_contranslan { 
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
   var $c46_seqtranslan = 0; 
   var $c46_seqtrans = 0; 
   var $c46_codhist = 0; 
   var $c46_obs = null; 
   var $c46_valor = 0; 
   var $c46_obrigatorio = 'f'; 
   var $c46_evento = 0; 
   var $c46_descricao = null; 
   var $c46_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c46_seqtranslan = int4 = Sequência 
                 c46_seqtrans = int4 = Cód. Contrans 
                 c46_codhist = int4 = Histórico 
                 c46_obs = text = Observações 
                 c46_valor = float8 = Valor 
                 c46_obrigatorio = bool = Obrigatório 
                 c46_evento = int4 = Evento Automatico 
                 c46_descricao = varchar(100) = Descrição 
                 c46_ordem = int4 = Ordem 
                 ";
   //funcao construtor da classe 
   function cl_contranslan() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("contranslan"); 
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
       $this->c46_seqtranslan = ($this->c46_seqtranslan == ""?@$GLOBALS["HTTP_POST_VARS"]["c46_seqtranslan"]:$this->c46_seqtranslan);
       $this->c46_seqtrans = ($this->c46_seqtrans == ""?@$GLOBALS["HTTP_POST_VARS"]["c46_seqtrans"]:$this->c46_seqtrans);
       $this->c46_codhist = ($this->c46_codhist == ""?@$GLOBALS["HTTP_POST_VARS"]["c46_codhist"]:$this->c46_codhist);
       $this->c46_obs = ($this->c46_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["c46_obs"]:$this->c46_obs);
       $this->c46_valor = ($this->c46_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["c46_valor"]:$this->c46_valor);
       $this->c46_obrigatorio = ($this->c46_obrigatorio == "f"?@$GLOBALS["HTTP_POST_VARS"]["c46_obrigatorio"]:$this->c46_obrigatorio);
       $this->c46_evento = ($this->c46_evento == ""?@$GLOBALS["HTTP_POST_VARS"]["c46_evento"]:$this->c46_evento);
       $this->c46_descricao = ($this->c46_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["c46_descricao"]:$this->c46_descricao);
       $this->c46_ordem = ($this->c46_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["c46_ordem"]:$this->c46_ordem);
     }else{
       $this->c46_seqtranslan = ($this->c46_seqtranslan == ""?@$GLOBALS["HTTP_POST_VARS"]["c46_seqtranslan"]:$this->c46_seqtranslan);
       $this->c46_seqtrans = ($this->c46_seqtrans == ""?@$GLOBALS["HTTP_POST_VARS"]["c46_seqtrans"]:$this->c46_seqtrans);
     }
   }
   // funcao para inclusao
   function incluir ($c46_seqtranslan){ 
      $this->atualizacampos();
     if($this->c46_codhist == null ){ 
       $this->erro_sql = " Campo Histórico nao Informado.";
       $this->erro_campo = "c46_codhist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c46_obs == null ){ 
       $this->erro_sql = " Campo Observações nao Informado.";
       $this->erro_campo = "c46_obs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c46_valor == null ){ 
       $this->c46_valor = "0";
     }
     if($this->c46_obrigatorio == null ){ 
       $this->erro_sql = " Campo Obrigatório nao Informado.";
       $this->erro_campo = "c46_obrigatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c46_evento == null ){ 
       $this->c46_evento = "0";
     }
     if($this->c46_ordem == null ){ 
       $this->erro_sql = " Campo Ordem nao Informado.";
       $this->erro_campo = "c46_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c46_seqtranslan == "" || $c46_seqtranslan == null ){
       $result = db_query("select nextval('contranslan_c46_seqtranslan_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: contranslan_c46_seqtranslan_seq do campo: c46_seqtranslan"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c46_seqtranslan = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from contranslan_c46_seqtranslan_seq");
       if(($result != false) && (pg_result($result,0,0) < $c46_seqtranslan)){
         $this->erro_sql = " Campo c46_seqtranslan maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c46_seqtranslan = $c46_seqtranslan; 
       }
     }
     if(($this->c46_seqtranslan == null) || ($this->c46_seqtranslan == "") ){ 
       $this->erro_sql = " Campo c46_seqtranslan nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into contranslan(
                                       c46_seqtranslan 
                                      ,c46_seqtrans 
                                      ,c46_codhist 
                                      ,c46_obs 
                                      ,c46_valor 
                                      ,c46_obrigatorio 
                                      ,c46_evento 
                                      ,c46_descricao 
                                      ,c46_ordem 
                       )
                values (
                                $this->c46_seqtranslan 
                               ,$this->c46_seqtrans 
                               ,$this->c46_codhist 
                               ,'$this->c46_obs' 
                               ,$this->c46_valor 
                               ,'$this->c46_obrigatorio' 
                               ,$this->c46_evento 
                               ,'$this->c46_descricao' 
                               ,$this->c46_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lançamentos Automáticos ($this->c46_seqtranslan) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lançamentos Automáticos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lançamentos Automáticos ($this->c46_seqtranslan) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c46_seqtranslan;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c46_seqtranslan));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6020,'$this->c46_seqtranslan','I')");
       $resac = db_query("insert into db_acount values($acount,815,6020,'','".AddSlashes(pg_result($resaco,0,'c46_seqtranslan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,815,5485,'','".AddSlashes(pg_result($resaco,0,'c46_seqtrans'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,815,5490,'','".AddSlashes(pg_result($resaco,0,'c46_codhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,815,6021,'','".AddSlashes(pg_result($resaco,0,'c46_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,815,6213,'','".AddSlashes(pg_result($resaco,0,'c46_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,815,6520,'','".AddSlashes(pg_result($resaco,0,'c46_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,815,7404,'','".AddSlashes(pg_result($resaco,0,'c46_evento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,815,18895,'','".AddSlashes(pg_result($resaco,0,'c46_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,815,18894,'','".AddSlashes(pg_result($resaco,0,'c46_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c46_seqtranslan=null) { 
      $this->atualizacampos();
     $sql = " update contranslan set ";
     $virgula = "";
     if(trim($this->c46_seqtranslan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c46_seqtranslan"])){ 
       $sql  .= $virgula." c46_seqtranslan = $this->c46_seqtranslan ";
       $virgula = ",";
       if(trim($this->c46_seqtranslan) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "c46_seqtranslan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c46_seqtrans)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c46_seqtrans"])){ 
       $sql  .= $virgula." c46_seqtrans = $this->c46_seqtrans ";
       $virgula = ",";
       if(trim($this->c46_seqtrans) == null ){ 
         $this->erro_sql = " Campo Cód. Contrans nao Informado.";
         $this->erro_campo = "c46_seqtrans";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c46_codhist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c46_codhist"])){ 
       $sql  .= $virgula." c46_codhist = $this->c46_codhist ";
       $virgula = ",";
       if(trim($this->c46_codhist) == null ){ 
         $this->erro_sql = " Campo Histórico nao Informado.";
         $this->erro_campo = "c46_codhist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c46_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c46_obs"])){ 
       $sql  .= $virgula." c46_obs = '$this->c46_obs' ";
       $virgula = ",";
       if(trim($this->c46_obs) == null ){ 
         $this->erro_sql = " Campo Observações nao Informado.";
         $this->erro_campo = "c46_obs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c46_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c46_valor"])){ 
        if(trim($this->c46_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c46_valor"])){ 
           $this->c46_valor = "0" ; 
        } 
       $sql  .= $virgula." c46_valor = $this->c46_valor ";
       $virgula = ",";
     }
     if(trim($this->c46_obrigatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c46_obrigatorio"])){ 
       $sql  .= $virgula." c46_obrigatorio = '$this->c46_obrigatorio' ";
       $virgula = ",";
       if(trim($this->c46_obrigatorio) == null ){ 
         $this->erro_sql = " Campo Obrigatório nao Informado.";
         $this->erro_campo = "c46_obrigatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c46_evento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c46_evento"])){ 
        if(trim($this->c46_evento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c46_evento"])){ 
           $this->c46_evento = "0" ; 
        } 
       $sql  .= $virgula." c46_evento = $this->c46_evento ";
       $virgula = ",";
     }
     if(trim($this->c46_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c46_descricao"])){ 
       $sql  .= $virgula." c46_descricao = '$this->c46_descricao' ";
       $virgula = ",";
     }
     if(trim($this->c46_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c46_ordem"])){ 
       $sql  .= $virgula." c46_ordem = $this->c46_ordem ";
       $virgula = ",";
       if(trim($this->c46_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem nao Informado.";
         $this->erro_campo = "c46_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c46_seqtranslan!=null){
       $sql .= " c46_seqtranslan = $this->c46_seqtranslan";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c46_seqtranslan));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6020,'$this->c46_seqtranslan','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c46_seqtranslan"]))
           $resac = db_query("insert into db_acount values($acount,815,6020,'".AddSlashes(pg_result($resaco,$conresaco,'c46_seqtranslan'))."','$this->c46_seqtranslan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c46_seqtrans"]))
           $resac = db_query("insert into db_acount values($acount,815,5485,'".AddSlashes(pg_result($resaco,$conresaco,'c46_seqtrans'))."','$this->c46_seqtrans',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c46_codhist"]))
           $resac = db_query("insert into db_acount values($acount,815,5490,'".AddSlashes(pg_result($resaco,$conresaco,'c46_codhist'))."','$this->c46_codhist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c46_obs"]))
           $resac = db_query("insert into db_acount values($acount,815,6021,'".AddSlashes(pg_result($resaco,$conresaco,'c46_obs'))."','$this->c46_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c46_valor"]))
           $resac = db_query("insert into db_acount values($acount,815,6213,'".AddSlashes(pg_result($resaco,$conresaco,'c46_valor'))."','$this->c46_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c46_obrigatorio"]))
           $resac = db_query("insert into db_acount values($acount,815,6520,'".AddSlashes(pg_result($resaco,$conresaco,'c46_obrigatorio'))."','$this->c46_obrigatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c46_evento"]))
           $resac = db_query("insert into db_acount values($acount,815,7404,'".AddSlashes(pg_result($resaco,$conresaco,'c46_evento'))."','$this->c46_evento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c46_descricao"]))
           $resac = db_query("insert into db_acount values($acount,815,18895,'".AddSlashes(pg_result($resaco,$conresaco,'c46_descricao'))."','$this->c46_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c46_ordem"]))
           $resac = db_query("insert into db_acount values($acount,815,18894,'".AddSlashes(pg_result($resaco,$conresaco,'c46_ordem'))."','$this->c46_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamentos Automáticos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c46_seqtranslan;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamentos Automáticos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c46_seqtranslan;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c46_seqtranslan;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c46_seqtranslan=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c46_seqtranslan));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6020,'$c46_seqtranslan','E')");
         $resac = db_query("insert into db_acount values($acount,815,6020,'','".AddSlashes(pg_result($resaco,$iresaco,'c46_seqtranslan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,815,5485,'','".AddSlashes(pg_result($resaco,$iresaco,'c46_seqtrans'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,815,5490,'','".AddSlashes(pg_result($resaco,$iresaco,'c46_codhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,815,6021,'','".AddSlashes(pg_result($resaco,$iresaco,'c46_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,815,6213,'','".AddSlashes(pg_result($resaco,$iresaco,'c46_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,815,6520,'','".AddSlashes(pg_result($resaco,$iresaco,'c46_obrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,815,7404,'','".AddSlashes(pg_result($resaco,$iresaco,'c46_evento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,815,18895,'','".AddSlashes(pg_result($resaco,$iresaco,'c46_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,815,18894,'','".AddSlashes(pg_result($resaco,$iresaco,'c46_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from contranslan
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c46_seqtranslan != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c46_seqtranslan = $c46_seqtranslan ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lançamentos Automáticos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c46_seqtranslan;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Lançamentos Automáticos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c46_seqtranslan;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c46_seqtranslan;
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
        $this->erro_sql   = "Record Vazio na Tabela:contranslan";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c46_seqtranslan=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contranslan ";
     $sql .= "      inner join conhist  on  conhist.c50_codhist = contranslan.c46_codhist";
     $sql .= "      inner join contrans  on  contrans.c45_seqtrans = contranslan.c46_seqtrans";
     $sql .= "      inner join conhistdoc  on  conhistdoc.c53_coddoc = contrans.c45_coddoc";
     $sql2 = "";
     if($dbwhere==""){
       if($c46_seqtranslan!=null ){
         $sql2 .= " where contranslan.c46_seqtranslan = $c46_seqtranslan "; 
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
   function sql_query_file ( $c46_seqtranslan=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from contranslan ";
     $sql2 = "";
     if($dbwhere==""){
       if($c46_seqtranslan!=null ){
         $sql2 .= " where contranslan.c46_seqtranslan = $c46_seqtranslan "; 
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
   function sql_query_lr ( $c46_seqtranslan=null,$campos="*",$ordem=null,$dbwhere="", $iInstit=null){
    
    /*
     * Alterada a função para passar o parâmetro da instituição
     * Quando não for informado nada no parâmetro, será utilziada a instituição da sessão
     */
    if ($iInstit == null) {
       $iInstit = db_getsession("DB_instit");
    }
    
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
     $sql .= " from contrans ";
     $sql .= "      inner join conhistdoc  on  conhistdoc.c53_coddoc = contrans.c45_coddoc";
     $sql .= "      inner join contranslan  on  contrans.c45_seqtrans = contranslan.c46_seqtrans";
     $sql .= "      inner join conhist  on  conhist.c50_codhist = contranslan.c46_codhist";
     $sql .= "      inner join contranslr  on  contranslr.c47_seqtranslan = contranslan.c46_seqtranslan and
                                  					            contranslr.c47_instit = ".$iInstit;
     $sql .= "      left join conplanoreduz deb1  on  contranslr.c47_debito = deb1.c61_reduz and deb1.c61_anousu=".db_getsession("DB_anousu");
     $sql .= "      left join conplano      deb2   on  deb2.c60_codcon      = deb1.c61_codcon and  deb2.c60_anousu = deb1.c61_anousu";
     $sql .= "      left join conplanoreduz cre1  on  contranslr.c47_credito = cre1.c61_reduz and cre1.c61_anousu=".db_getsession("DB_anousu");
     $sql .= "      left join conplano      cre2   on  cre2.c60_codcon=cre1.c61_codcon and cre2.c60_anousu=cre1.c61_anousu";
     
     $sql2 = "";
     if($dbwhere==""){
       if($c46_seqtranslan!=null ){
         $sql2 .= " where c45_anousu = ".db_getsession("DB_anousu")." and  contranslan.c46_seqtranslan = $c46_seqtranslan ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where  c45_anousu = ".db_getsession("DB_anousu")." and  $dbwhere";
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
   function sql_query_receita ( $c46_seqtranslan=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from contrans ";
     $sql .= "      inner join conhistdoc  on  conhistdoc.c53_coddoc = contrans.c45_coddoc";
     $sql .= "      inner join contranslan  on  contrans.c45_seqtrans = contranslan.c46_seqtrans";
     $sql .= "      inner join conhist  on  conhist.c50_codhist = contranslan.c46_codhist";
     $sql .= "      inner join contranslr  on  contranslr.c47_seqtranslan = contranslan.c46_seqtranslan and contranslr.c47_instit = ".db_getsession("DB_instit");
     /*
     $sql .= "      left join conplanoreduz deb1  on  contranslr.c47_debito = deb1.c61_reduz";
     $sql .= "      left join conplano      deb2   on  deb2.c60_codcon      = deb1.c61_codcon";
     $sql .= "      left join conplanoreduz cre1  on  contranslr.c47_credito = cre1.c61_reduz";
     $sql .= "      left join conplano      cre2   on  cre2.c60_codcon      = cre1.c61_codcon";
     */
     $sql2 = "";
     if($dbwhere==""){
       if($c46_seqtranslan!=null ){
         $sql2 .= " where c45_anousu = ".db_getsession("DB_anousu")." and  contranslan.c46_seqtranslan = $c46_seqtranslan ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where  c45_anousu = ".db_getsession("DB_anousu")." and  $dbwhere";
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

   /**
    * Pesquisa regras de lancamento
    * - semelhante ao metodo $this->sql_query_lr(), mas com left join na contranslr
    *
    * @param string $c46_seqtranslan
    * @param string $campos
    * @param string $ordem
    * @param string $dbwhere
    * @param integer $iInstit
    * @access public
    * @return string
    */
   public function sql_query_lr_documentoObrigatorio ( $c46_seqtranslan=null,$campos="*",$ordem=null,$dbwhere="", $iInstit=null){
    
    /*
     * Alterada a função para passar o parâmetro da instituição
     * Quando não for informado nada no parâmetro, será utilziada a instituição da sessão
     */
    if ($iInstit == null) {
       $iInstit = db_getsession("DB_instit");
    }
    
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
     $sql .= " from contrans ";
     $sql .= "      inner join conhistdoc  on  conhistdoc.c53_coddoc = contrans.c45_coddoc";
     $sql .= "      inner join contranslan  on  contrans.c45_seqtrans = contranslan.c46_seqtrans";
     $sql .= "      inner join conhist  on  conhist.c50_codhist = contranslan.c46_codhist";
     $sql .= "      left join contranslr  on  contranslr.c47_seqtranslan = contranslan.c46_seqtranslan and
                                  					            contranslr.c47_instit = ".$iInstit;
     $sql .= "      left join conplanoreduz deb1  on  contranslr.c47_debito = deb1.c61_reduz and deb1.c61_anousu=".db_getsession("DB_anousu");
     $sql .= "      left join conplano      deb2   on  deb2.c60_codcon      = deb1.c61_codcon and  deb2.c60_anousu = deb1.c61_anousu";
     $sql .= "      left join conplanoreduz cre1  on  contranslr.c47_credito = cre1.c61_reduz and cre1.c61_anousu=".db_getsession("DB_anousu");
     $sql .= "      left join conplano      cre2   on  cre2.c60_codcon=cre1.c61_codcon and cre2.c60_anousu=cre1.c61_anousu";
     
     $sql2 = "";
     if($dbwhere==""){
       if($c46_seqtranslan!=null ){
         $sql2 .= " where c45_anousu = ".db_getsession("DB_anousu")." and  contranslan.c46_seqtranslan = $c46_seqtranslan ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where  c45_anousu = ".db_getsession("DB_anousu")." and  $dbwhere";
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