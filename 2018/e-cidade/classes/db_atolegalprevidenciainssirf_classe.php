<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
//MODULO: pessoal
//CLASSE DA ENTIDADE atolegalprevidenciainssirf
class cl_atolegalprevidenciainssirf { 
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
   var $rh180_sequencial = 0; 
   var $rh180_instituicao = 0; 
   var $rh180_inssirf = 0; 
   var $rh180_atolegal = 0; 
   var $rh180_numero = 0; 
   var $rh180_ano = 0; 
   var $rh180_datapublicacao_dia = null; 
   var $rh180_datapublicacao_mes = null; 
   var $rh180_datapublicacao_ano = null; 
   var $rh180_datapublicacao = null; 
   var $rh180_datainiciovigencia_dia = null; 
   var $rh180_datainiciovigencia_mes = null; 
   var $rh180_datainiciovigencia_ano = null; 
   var $rh180_datainiciovigencia = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh180_sequencial = int4 = Código 
                 rh180_instituicao = int4 = Código da Instituição 
                 rh180_inssirf = int4 = INSSIRF 
                 rh180_atolegal = int4 = Ato Legal 
                 rh180_numero = int4 = Número do Ato 
                 rh180_ano = int4 = Ano 
                 rh180_datapublicacao = date = Data de Publicação 
                 rh180_datainiciovigencia = date = Data de Início de Vigência 
                 ";
   //funcao construtor da classe 
   function cl_atolegalprevidenciainssirf() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("atolegalprevidenciainssirf"); 
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
       $this->rh180_sequencial = ($this->rh180_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_sequencial"]:$this->rh180_sequencial);
       $this->rh180_instituicao = ($this->rh180_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_instituicao"]:$this->rh180_instituicao);
       $this->rh180_inssirf = ($this->rh180_inssirf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_inssirf"]:$this->rh180_inssirf);
       $this->rh180_atolegal = ($this->rh180_atolegal == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_atolegal"]:$this->rh180_atolegal);
       $this->rh180_numero = ($this->rh180_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_numero"]:$this->rh180_numero);
       $this->rh180_ano = ($this->rh180_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_ano"]:$this->rh180_ano);
       if($this->rh180_datapublicacao == ""){
         $this->rh180_datapublicacao_dia = ($this->rh180_datapublicacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_datapublicacao_dia"]:$this->rh180_datapublicacao_dia);
         $this->rh180_datapublicacao_mes = ($this->rh180_datapublicacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_datapublicacao_mes"]:$this->rh180_datapublicacao_mes);
         $this->rh180_datapublicacao_ano = ($this->rh180_datapublicacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_datapublicacao_ano"]:$this->rh180_datapublicacao_ano);
         if($this->rh180_datapublicacao_dia != ""){
            $this->rh180_datapublicacao = $this->rh180_datapublicacao_ano."-".$this->rh180_datapublicacao_mes."-".$this->rh180_datapublicacao_dia;
         }
       }
       if($this->rh180_datainiciovigencia == ""){
         $this->rh180_datainiciovigencia_dia = ($this->rh180_datainiciovigencia_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_datainiciovigencia_dia"]:$this->rh180_datainiciovigencia_dia);
         $this->rh180_datainiciovigencia_mes = ($this->rh180_datainiciovigencia_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_datainiciovigencia_mes"]:$this->rh180_datainiciovigencia_mes);
         $this->rh180_datainiciovigencia_ano = ($this->rh180_datainiciovigencia_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_datainiciovigencia_ano"]:$this->rh180_datainiciovigencia_ano);
         if($this->rh180_datainiciovigencia_dia != ""){
            $this->rh180_datainiciovigencia = $this->rh180_datainiciovigencia_ano."-".$this->rh180_datainiciovigencia_mes."-".$this->rh180_datainiciovigencia_dia;
         }
       }
     }else{
       $this->rh180_sequencial = ($this->rh180_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh180_sequencial"]:$this->rh180_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($rh180_sequencial){ 
      $this->atualizacampos();
     if($this->rh180_instituicao == null ){ 
       $this->erro_sql = " Campo Código da Instituição não informado.";
       $this->erro_campo = "rh180_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh180_inssirf == null ){ 
       $this->erro_sql = " Campo INSSIRF não informado.";
       $this->erro_campo = "rh180_inssirf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh180_atolegal == null ){ 
       $this->erro_sql = " Campo Ato Legal não informado.";
       $this->erro_campo = "rh180_atolegal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh180_numero == null ){ 
       $this->erro_sql = " Campo Número do Ato não informado.";
       $this->erro_campo = "rh180_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh180_ano == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "rh180_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh180_datapublicacao == null ){ 
       $this->erro_sql = " Campo Data de Publicação não informado.";
       $this->erro_campo = "rh180_datapublicacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh180_datainiciovigencia == null ){ 
       $this->erro_sql = " Campo Data de Início de Vigência não informado.";
       $this->erro_campo = "rh180_datainiciovigencia_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh180_sequencial == "" || $rh180_sequencial == null ){
       $result = db_query("select nextval('atolegalprevidenciainssirf_rh180_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: atolegalprevidenciainssirf_rh180_sequencial_seq do campo: rh180_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh180_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from atolegalprevidenciainssirf_rh180_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh180_sequencial)){
         $this->erro_sql = " Campo rh180_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh180_sequencial = $rh180_sequencial; 
       }
     }
     if(($this->rh180_sequencial == null) || ($this->rh180_sequencial == "") ){ 
       $this->erro_sql = " Campo rh180_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into atolegalprevidenciainssirf(
                                       rh180_sequencial 
                                      ,rh180_instituicao 
                                      ,rh180_inssirf 
                                      ,rh180_atolegal 
                                      ,rh180_numero 
                                      ,rh180_ano 
                                      ,rh180_datapublicacao 
                                      ,rh180_datainiciovigencia 
                       )
                values (
                                $this->rh180_sequencial 
                               ,$this->rh180_instituicao 
                               ,$this->rh180_inssirf 
                               ,$this->rh180_atolegal 
                               ,$this->rh180_numero 
                               ,$this->rh180_ano 
                               ,".($this->rh180_datapublicacao == "null" || $this->rh180_datapublicacao == ""?"null":"'".$this->rh180_datapublicacao."'")." 
                               ,".($this->rh180_datainiciovigencia == "null" || $this->rh180_datainiciovigencia == ""?"null":"'".$this->rh180_datainiciovigencia."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Previdência do Ato Legal ($this->rh180_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Previdência do Ato Legal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Previdência do Ato Legal ($this->rh180_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh180_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh180_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21943,'$this->rh180_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3951,21943,'','".AddSlashes(pg_result($resaco,0,'rh180_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3951,21950,'','".AddSlashes(pg_result($resaco,0,'rh180_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3951,21944,'','".AddSlashes(pg_result($resaco,0,'rh180_inssirf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3951,21945,'','".AddSlashes(pg_result($resaco,0,'rh180_atolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3951,21946,'','".AddSlashes(pg_result($resaco,0,'rh180_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3951,21947,'','".AddSlashes(pg_result($resaco,0,'rh180_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3951,21948,'','".AddSlashes(pg_result($resaco,0,'rh180_datapublicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3951,21949,'','".AddSlashes(pg_result($resaco,0,'rh180_datainiciovigencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($rh180_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update atolegalprevidenciainssirf set ";
     $virgula = "";
     if(trim($this->rh180_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh180_sequencial"])){ 
       $sql  .= $virgula." rh180_sequencial = $this->rh180_sequencial ";
       $virgula = ",";
       if(trim($this->rh180_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "rh180_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh180_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh180_instituicao"])){ 
       $sql  .= $virgula." rh180_instituicao = $this->rh180_instituicao ";
       $virgula = ",";
       if(trim($this->rh180_instituicao) == null ){ 
         $this->erro_sql = " Campo Código da Instituição não informado.";
         $this->erro_campo = "rh180_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh180_inssirf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh180_inssirf"])){ 
       $sql  .= $virgula." rh180_inssirf = $this->rh180_inssirf ";
       $virgula = ",";
       if(trim($this->rh180_inssirf) == null ){ 
         $this->erro_sql = " Campo INSSIRF não informado.";
         $this->erro_campo = "rh180_inssirf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh180_atolegal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh180_atolegal"])){ 
       $sql  .= $virgula." rh180_atolegal = $this->rh180_atolegal ";
       $virgula = ",";
       if(trim($this->rh180_atolegal) == null ){ 
         $this->erro_sql = " Campo Ato Legal não informado.";
         $this->erro_campo = "rh180_atolegal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh180_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh180_numero"])){ 
       $sql  .= $virgula." rh180_numero = $this->rh180_numero ";
       $virgula = ",";
       if(trim($this->rh180_numero) == null ){ 
         $this->erro_sql = " Campo Número do Ato não informado.";
         $this->erro_campo = "rh180_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh180_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh180_ano"])){ 
       $sql  .= $virgula." rh180_ano = $this->rh180_ano ";
       $virgula = ",";
       if(trim($this->rh180_ano) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "rh180_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh180_datapublicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh180_datapublicacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh180_datapublicacao_dia"] !="") ){ 
       $sql  .= $virgula." rh180_datapublicacao = '$this->rh180_datapublicacao' ";
       $virgula = ",";
       if(trim($this->rh180_datapublicacao) == null ){ 
         $this->erro_sql = " Campo Data de Publicação não informado.";
         $this->erro_campo = "rh180_datapublicacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh180_datapublicacao_dia"])){ 
         $sql  .= $virgula." rh180_datapublicacao = null ";
         $virgula = ",";
         if(trim($this->rh180_datapublicacao) == null ){ 
           $this->erro_sql = " Campo Data de Publicação não informado.";
           $this->erro_campo = "rh180_datapublicacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh180_datainiciovigencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh180_datainiciovigencia_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh180_datainiciovigencia_dia"] !="") ){ 
       $sql  .= $virgula." rh180_datainiciovigencia = '$this->rh180_datainiciovigencia' ";
       $virgula = ",";
       if(trim($this->rh180_datainiciovigencia) == null ){ 
         $this->erro_sql = " Campo Data de Início de Vigência não informado.";
         $this->erro_campo = "rh180_datainiciovigencia_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh180_datainiciovigencia_dia"])){ 
         $sql  .= $virgula." rh180_datainiciovigencia = null ";
         $virgula = ",";
         if(trim($this->rh180_datainiciovigencia) == null ){ 
           $this->erro_sql = " Campo Data de Início de Vigência não informado.";
           $this->erro_campo = "rh180_datainiciovigencia_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($rh180_sequencial!=null){
       $sql .= " rh180_sequencial = $this->rh180_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh180_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21943,'$this->rh180_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh180_sequencial"]) || $this->rh180_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3951,21943,'".AddSlashes(pg_result($resaco,$conresaco,'rh180_sequencial'))."','$this->rh180_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh180_instituicao"]) || $this->rh180_instituicao != "")
             $resac = db_query("insert into db_acount values($acount,3951,21950,'".AddSlashes(pg_result($resaco,$conresaco,'rh180_instituicao'))."','$this->rh180_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh180_inssirf"]) || $this->rh180_inssirf != "")
             $resac = db_query("insert into db_acount values($acount,3951,21944,'".AddSlashes(pg_result($resaco,$conresaco,'rh180_inssirf'))."','$this->rh180_inssirf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh180_atolegal"]) || $this->rh180_atolegal != "")
             $resac = db_query("insert into db_acount values($acount,3951,21945,'".AddSlashes(pg_result($resaco,$conresaco,'rh180_atolegal'))."','$this->rh180_atolegal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh180_numero"]) || $this->rh180_numero != "")
             $resac = db_query("insert into db_acount values($acount,3951,21946,'".AddSlashes(pg_result($resaco,$conresaco,'rh180_numero'))."','$this->rh180_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh180_ano"]) || $this->rh180_ano != "")
             $resac = db_query("insert into db_acount values($acount,3951,21947,'".AddSlashes(pg_result($resaco,$conresaco,'rh180_ano'))."','$this->rh180_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh180_datapublicacao"]) || $this->rh180_datapublicacao != "")
             $resac = db_query("insert into db_acount values($acount,3951,21948,'".AddSlashes(pg_result($resaco,$conresaco,'rh180_datapublicacao'))."','$this->rh180_datapublicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh180_datainiciovigencia"]) || $this->rh180_datainiciovigencia != "")
             $resac = db_query("insert into db_acount values($acount,3951,21949,'".AddSlashes(pg_result($resaco,$conresaco,'rh180_datainiciovigencia'))."','$this->rh180_datainiciovigencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Previdência do Ato Legal não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh180_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Previdência do Ato Legal não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh180_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh180_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($rh180_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh180_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21943,'$rh180_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3951,21943,'','".AddSlashes(pg_result($resaco,$iresaco,'rh180_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3951,21950,'','".AddSlashes(pg_result($resaco,$iresaco,'rh180_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3951,21944,'','".AddSlashes(pg_result($resaco,$iresaco,'rh180_inssirf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3951,21945,'','".AddSlashes(pg_result($resaco,$iresaco,'rh180_atolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3951,21946,'','".AddSlashes(pg_result($resaco,$iresaco,'rh180_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3951,21947,'','".AddSlashes(pg_result($resaco,$iresaco,'rh180_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3951,21948,'','".AddSlashes(pg_result($resaco,$iresaco,'rh180_datapublicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3951,21949,'','".AddSlashes(pg_result($resaco,$iresaco,'rh180_datainiciovigencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from atolegalprevidenciainssirf
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh180_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh180_sequencial = $rh180_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Previdência do Ato Legal não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh180_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Previdência do Ato Legal não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh180_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh180_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:atolegalprevidenciainssirf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($rh180_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from atolegalprevidenciainssirf ";
     $sql .= "      inner join inssirf  on  inssirf.r33_codigo = atolegalprevidenciainssirf.rh180_inssirf and  inssirf.r33_instit = atolegalprevidenciainssirf.rh180_instituicao";
     $sql .= "      inner join atolegalprevidencia  on  atolegalprevidencia.rh179_sequencial = atolegalprevidenciainssirf.rh180_atolegal";
     $sql .= "      inner join db_config  on  db_config.codigo = inssirf.r33_instit";
     $sql .= "      left  join orcelemento  on  orcelemento.o56_codele = inssirf.r33_codele and  orcelemento.o56_anousu = inssirf.r33_anousu";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh180_sequencial)) {
         $sql2 .= " where atolegalprevidenciainssirf.rh180_sequencial = $rh180_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($rh180_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from atolegalprevidenciainssirf ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh180_sequencial)){
         $sql2 .= " where atolegalprevidenciainssirf.rh180_sequencial = $rh180_sequencial "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

}
