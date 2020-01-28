<?php
/*
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
//CLASSE DA ENTIDADE rhgeracaofolhaarquivo
class cl_rhgeracaofolhaarquivo {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $rh105_sequencial = 0;
   var $rh105_dtgeracao_dia = null;
   var $rh105_dtgeracao_mes = null;
   var $rh105_dtgeracao_ano = null;
   var $rh105_dtgeracao = null;
   var $rh105_dtdeposito_dia = null;
   var $rh105_dtdeposito_mes = null;
   var $rh105_dtdeposito_ano = null;
   var $rh105_dtdeposito = null;
   var $rh105_codarq = 0;
   var $rh105_codbcofebraban = null;
   var $rh105_tipoarq = 0;
   var $rh105_folha = 0;
   var $rh105_arquivotxt = null;
   var $rh105_instit = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 rh105_sequencial = int4 = Sequencial
                 rh105_dtgeracao = date = Data da Geração
                 rh105_dtdeposito = date = Data do Depósito
                 rh105_codarq = int4 = Código do Arquivo
                 rh105_codbcofebraban = varchar(10) = Código do Banco Febraban
                 rh105_tipoarq = int4 = Tipo de Arquivo
                 rh105_folha = int4 = Folha
                 rh105_arquivotxt = text = Arquivo Gerado
                 rh105_instit = int4 = Instituição
                 ";
   //funcao construtor da classe
   function cl_rhgeracaofolhaarquivo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhgeracaofolhaarquivo");
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
       $this->rh105_sequencial = ($this->rh105_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh105_sequencial"]:$this->rh105_sequencial);
       if($this->rh105_dtgeracao == ""){
         $this->rh105_dtgeracao_dia = @$GLOBALS["HTTP_POST_VARS"]["rh105_dtgeracao_dia"];
         $this->rh105_dtgeracao_mes = @$GLOBALS["HTTP_POST_VARS"]["rh105_dtgeracao_mes"];
         $this->rh105_dtgeracao_ano = @$GLOBALS["HTTP_POST_VARS"]["rh105_dtgeracao_ano"];
         if($this->rh105_dtgeracao_dia != ""){
            $this->rh105_dtgeracao = $this->rh105_dtgeracao_ano."-".$this->rh105_dtgeracao_mes."-".$this->rh105_dtgeracao_dia;
         }
       }
       if($this->rh105_dtdeposito == ""){
         $this->rh105_dtdeposito_dia = @$GLOBALS["HTTP_POST_VARS"]["rh105_dtdeposito_dia"];
         $this->rh105_dtdeposito_mes = @$GLOBALS["HTTP_POST_VARS"]["rh105_dtdeposito_mes"];
         $this->rh105_dtdeposito_ano = @$GLOBALS["HTTP_POST_VARS"]["rh105_dtdeposito_ano"];
         if($this->rh105_dtdeposito_dia != ""){
            $this->rh105_dtdeposito = $this->rh105_dtdeposito_ano."-".$this->rh105_dtdeposito_mes."-".$this->rh105_dtdeposito_dia;
         }
       }
       $this->rh105_codarq = ($this->rh105_codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh105_codarq"]:$this->rh105_codarq);
       $this->rh105_codbcofebraban = ($this->rh105_codbcofebraban == ""?@$GLOBALS["HTTP_POST_VARS"]["rh105_codbcofebraban"]:$this->rh105_codbcofebraban);
       $this->rh105_tipoarq = ($this->rh105_tipoarq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh105_tipoarq"]:$this->rh105_tipoarq);
       $this->rh105_folha = ($this->rh105_folha == ""?@$GLOBALS["HTTP_POST_VARS"]["rh105_folha"]:$this->rh105_folha);
       $this->rh105_arquivotxt = ($this->rh105_arquivotxt == ""?@$GLOBALS["HTTP_POST_VARS"]["rh105_arquivotxt"]:$this->rh105_arquivotxt);
       $this->rh105_instit = ($this->rh105_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh105_instit"]:$this->rh105_instit);
     }else{
       $this->rh105_sequencial = ($this->rh105_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh105_sequencial"]:$this->rh105_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh105_sequencial){
      $this->atualizacampos();
     if($this->rh105_dtgeracao == null ){
       $this->erro_sql = " Campo Data da Geração nao Informado.";
       $this->erro_campo = "rh105_dtgeracao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh105_dtdeposito == null ){
       $this->erro_sql = " Campo Data do Depósito nao Informado.";
       $this->erro_campo = "rh105_dtdeposito_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh105_codarq == null ){
       $this->erro_sql = " Campo Código do Arquivo nao Informado.";
       $this->erro_campo = "rh105_codarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh105_tipoarq == null ){
       $this->erro_sql = " Campo Tipo de Arquivo nao Informado.";
       $this->erro_campo = "rh105_tipoarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh105_folha == null ){
       $this->erro_sql = " Campo Folha nao Informado.";
       $this->erro_campo = "rh105_folha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh105_arquivotxt == null ){
       $this->erro_sql = " Campo Arquivo Gerado nao Informado.";
       $this->erro_campo = "rh105_arquivotxt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh105_instit == null ){
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "rh105_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh105_sequencial == "" || $rh105_sequencial == null ){
       $result = @db_query("select nextval('rhgeracaofolhaarquivo_rh105_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhgeracaofolhaarquivo_rh105_sequencial_seq do campo: rh105_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->rh105_sequencial = pg_result($result,0,0);
     }else{
       $result = @db_query("select last_value from rhgeracaofolhaarquivo_rh105_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh105_sequencial)){
         $this->erro_sql = " Campo rh105_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh105_sequencial = $rh105_sequencial;
       }
     }
     if(($this->rh105_sequencial == null) || ($this->rh105_sequencial == "") ){
       $this->erro_sql = " Campo rh105_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @db_query("insert into rhgeracaofolhaarquivo(
                                       rh105_sequencial
                                      ,rh105_dtgeracao
                                      ,rh105_dtdeposito
                                      ,rh105_codarq
                                      ,rh105_codbcofebraban
                                      ,rh105_tipoarq
                                      ,rh105_folha
                                      ,rh105_arquivotxt
                                      ,rh105_instit
                       )
                values (
                                $this->rh105_sequencial
                               ,".($this->rh105_dtgeracao == "null" || $this->rh105_dtgeracao == ""?"null":"'".$this->rh105_dtgeracao."'")."
                               ,".($this->rh105_dtdeposito == "null" || $this->rh105_dtdeposito == ""?"null":"'".$this->rh105_dtdeposito."'")."
                               ,$this->rh105_codarq
                               ,'$this->rh105_codbcofebraban'
                               ,$this->rh105_tipoarq
                               ,$this->rh105_folha
                               ,'$this->rh105_arquivotxt'
                               ,$this->rh105_instit
                      )");
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhgeracaofolhaarquivo ($this->rh105_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhgeracaofolhaarquivo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhgeracaofolhaarquivo ($this->rh105_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh105_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $resaco = $this->sql_record($this->sql_query_file($this->rh105_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,18135,'$this->rh105_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3199,18135,'','".pg_result($resaco,0,'rh105_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18136,'','".pg_result($resaco,0,'rh105_dtgeracao')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18137,'','".pg_result($resaco,0,'rh105_dtdeposito')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18138,'','".pg_result($resaco,0,'rh105_codarq')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18139,'','".pg_result($resaco,0,'rh105_codbcofebraban')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18140,'','".pg_result($resaco,0,'rh105_tipoarq')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18141,'','".pg_result($resaco,0,'rh105_folha')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18142,'','".pg_result($resaco,0,'rh105_arquivotxt')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18146,'','".pg_result($resaco,0,'rh105_instit')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($rh105_sequencial=null) {
      $this->atualizacampos();
     $sql = " update rhgeracaofolhaarquivo set ";
     $virgula = "";
     if(trim($this->rh105_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh105_sequencial"])){
        if(trim($this->rh105_sequencial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh105_sequencial"])){
           $this->rh105_sequencial = "0" ;
        }
       $sql  .= $virgula." rh105_sequencial = $this->rh105_sequencial ";
       $virgula = ",";
       if(trim($this->rh105_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh105_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh105_dtgeracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh105_dtgeracao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh105_dtgeracao_dia"] !="") ){
       $sql  .= $virgula." rh105_dtgeracao = '$this->rh105_dtgeracao' ";
       $virgula = ",";
       if(trim($this->rh105_dtgeracao) == null ){
         $this->erro_sql = " Campo Data da Geração nao Informado.";
         $this->erro_campo = "rh105_dtgeracao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh105_dtgeracao_dia"])){
         $sql  .= $virgula." rh105_dtgeracao = null ";
         $virgula = ",";
         if(trim($this->rh105_dtgeracao) == null ){
           $this->erro_sql = " Campo Data da Geração nao Informado.";
           $this->erro_campo = "rh105_dtgeracao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh105_dtdeposito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh105_dtdeposito_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh105_dtdeposito_dia"] !="") ){
       $sql  .= $virgula." rh105_dtdeposito = '$this->rh105_dtdeposito' ";
       $virgula = ",";
       if(trim($this->rh105_dtdeposito) == null ){
         $this->erro_sql = " Campo Data do Depósito nao Informado.";
         $this->erro_campo = "rh105_dtdeposito_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh105_dtdeposito_dia"])){
         $sql  .= $virgula." rh105_dtdeposito = null ";
         $virgula = ",";
         if(trim($this->rh105_dtdeposito) == null ){
           $this->erro_sql = " Campo Data do Depósito nao Informado.";
           $this->erro_campo = "rh105_dtdeposito_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh105_codarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh105_codarq"])){
        if(trim($this->rh105_codarq)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh105_codarq"])){
           $this->rh105_codarq = "0" ;
        }
       $sql  .= $virgula." rh105_codarq = $this->rh105_codarq ";
       $virgula = ",";
       if(trim($this->rh105_codarq) == null ){
         $this->erro_sql = " Campo Código do Arquivo nao Informado.";
         $this->erro_campo = "rh105_codarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh105_codbcofebraban)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh105_codbcofebraban"])){
       $sql  .= $virgula." rh105_codbcofebraban = '$this->rh105_codbcofebraban' ";
       $virgula = ",";
     }
     if(trim($this->rh105_tipoarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh105_tipoarq"])){
        if(trim($this->rh105_tipoarq)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh105_tipoarq"])){
           $this->rh105_tipoarq = "0" ;
        }
       $sql  .= $virgula." rh105_tipoarq = $this->rh105_tipoarq ";
       $virgula = ",";
       if(trim($this->rh105_tipoarq) == null ){
         $this->erro_sql = " Campo Tipo de Arquivo nao Informado.";
         $this->erro_campo = "rh105_tipoarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh105_folha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh105_folha"])){
        if(trim($this->rh105_folha)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh105_folha"])){
           $this->rh105_folha = "0" ;
        }
       $sql  .= $virgula." rh105_folha = $this->rh105_folha ";
       $virgula = ",";
       if(trim($this->rh105_folha) == null ){
         $this->erro_sql = " Campo Folha nao Informado.";
         $this->erro_campo = "rh105_folha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh105_arquivotxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh105_arquivotxt"])){
       $sql  .= $virgula." rh105_arquivotxt = '$this->rh105_arquivotxt' ";
       $virgula = ",";
       if(trim($this->rh105_arquivotxt) == null ){
         $this->erro_sql = " Campo Arquivo Gerado nao Informado.";
         $this->erro_campo = "rh105_arquivotxt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh105_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh105_instit"])){
        if(trim($this->rh105_instit)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh105_instit"])){
           $this->rh105_instit = "0" ;
        }
       $sql  .= $virgula." rh105_instit = $this->rh105_instit ";
       $virgula = ",";
       if(trim($this->rh105_instit) == null ){
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "rh105_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where  rh105_sequencial = $this->rh105_sequencial
";
     $resaco = $this->sql_record($this->sql_query_file($this->rh105_sequencial));
     if($this->numrows>0){       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,18135,'$this->rh105_sequencial','A')");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh105_sequencial"]))
         $resac = db_query("insert into db_acount values($acount,3199,18135,'".pg_result($resaco,0,'rh105_sequencial')."','$this->rh105_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh105_dtgeracao"]))
         $resac = db_query("insert into db_acount values($acount,3199,18136,'".pg_result($resaco,0,'rh105_dtgeracao')."','$this->rh105_dtgeracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh105_dtdeposito"]))
         $resac = db_query("insert into db_acount values($acount,3199,18137,'".pg_result($resaco,0,'rh105_dtdeposito')."','$this->rh105_dtdeposito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh105_codarq"]))
         $resac = db_query("insert into db_acount values($acount,3199,18138,'".pg_result($resaco,0,'rh105_codarq')."','$this->rh105_codarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh105_codbcofebraban"]))
         $resac = db_query("insert into db_acount values($acount,3199,18139,'".pg_result($resaco,0,'rh105_codbcofebraban')."','$this->rh105_codbcofebraban',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh105_tipoarq"]))
         $resac = db_query("insert into db_acount values($acount,3199,18140,'".pg_result($resaco,0,'rh105_tipoarq')."','$this->rh105_tipoarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh105_folha"]))
         $resac = db_query("insert into db_acount values($acount,3199,18141,'".pg_result($resaco,0,'rh105_folha')."','$this->rh105_folha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh105_arquivotxt"]))
         $resac = db_query("insert into db_acount values($acount,3199,18142,'".pg_result($resaco,0,'rh105_arquivotxt')."','$this->rh105_arquivotxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh105_instit"]))
         $resac = db_query("insert into db_acount values($acount,3199,18146,'".pg_result($resaco,0,'rh105_instit')."','$this->rh105_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $result = @db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhgeracaofolhaarquivo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh105_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhgeracaofolhaarquivo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($rh105_sequencial=null) {
     $this->atualizacampos(true);
     $resaco = $this->sql_record($this->sql_query_file($this->rh105_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountkey values($acount,18135,'$this->rh105_sequencial','E')");
       $resac = db_query("insert into db_acount values($acount,3199,18135,'','".pg_result($resaco,0,'rh105_sequencial')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18136,'','".pg_result($resaco,0,'rh105_dtgeracao')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18137,'','".pg_result($resaco,0,'rh105_dtdeposito')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18138,'','".pg_result($resaco,0,'rh105_codarq')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18139,'','".pg_result($resaco,0,'rh105_codbcofebraban')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18140,'','".pg_result($resaco,0,'rh105_tipoarq')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18141,'','".pg_result($resaco,0,'rh105_folha')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18142,'','".pg_result($resaco,0,'rh105_arquivotxt')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3199,18146,'','".pg_result($resaco,0,'rh105_instit')."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     $sql = " delete from rhgeracaofolhaarquivo
                    where ";
     $sql2 = "";
      if($this->rh105_sequencial != ""){
      if($sql2!=""){
        $sql2 .= " and ";
      }
      $sql2 .= " rh105_sequencial = $this->rh105_sequencial ";
}
     $result = @db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhgeracaofolhaarquivo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->rh105_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhgeracaofolhaarquivo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh105_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }
     }
   }
   // funcao do recordset
   function sql_record($sql) {
     $result = @db_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $rh105_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from rhgeracaofolhaarquivo ";
     $sql .= "      inner join db_bancos  on  db_bancos.db90_codban = rhgeracaofolhaarquivo.rh105_codbcofebraban";
     $sql .= "      inner join rharqbanco  on  rharqbanco.rh34_codarq = rhgeracaofolhaarquivo.rh105_codarq and  rharqbanco.rh34_instit = rhgeracaofolhaarquivo.rh105_instit";
     $sql .= "      inner join db_config  on  db_config.codigo = rharqbanco.rh34_instit";
     $sql .= "      inner join db_bancos  as a on   a.db90_codban = rharqbanco.rh34_codban";
     $sql .= "      inner join db_config  as b on   b.codigo = rharqbanco.rh34_instit";
     $sql .= "      inner join db_bancos  as c on   c.db90_codban = rharqbanco.rh34_codban";
     $sql2 = "";
     if($dbwhere==""){
       if($rh105_sequencial!=null ){
         $sql2 .= " where rhgeracaofolhaarquivo.rh105_sequencial = $rh105_sequencial ";
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
   function sql_query_file ( $rh105_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from rhgeracaofolhaarquivo ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh105_sequencial!=null ){
         $sql2 .= " where rhgeracaofolhaarquivo.rh105_sequencial = $rh105_sequencial ";
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

   function salvaArquivoTXT($sCaminhoArquivoTXT){
     $hArquivoTxt   = fopen($sCaminhoArquivoTXT,"r");
     $sTextoArquivo = "";
     if($hArquivoTxt){
       while(!feof($hArquivoTxt)){
         $sTextoArquivo .= fgets($hArquivoTxt);
       }
       return $sTextoArquivo;
     } else {
       $this->erro_msg   .= "Não foi possivel abrir o Arquivo TXT";
       $this->erro_status = "0";
       fclose($hArquivoTxt);
       return false;
     }

   }
}
?>