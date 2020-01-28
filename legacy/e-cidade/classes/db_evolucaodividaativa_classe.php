<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

//MODULO: divida
//CLASSE DA ENTIDADE evolucaodividaativa
class cl_evolucaodividaativa {
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
   var $v30_sequencial = 0;
   var $v30_receita = 0;
   var $v30_datageracao_dia = null;
   var $v30_datageracao_mes = null;
   var $v30_datageracao_ano = null;
   var $v30_datageracao = null;
   var $v30_valorhistorico = 0;
   var $v30_valorcorrecao = 0;
   var $v30_valorpagoparcialhistorico = 0;
   var $v30_valorpagoparcial = 0;
   var $v30_valorpago = 0;
   var $v30_valorcancelado = 0;
   var $v30_valordesconto = 0;
   var $v30_valorpagohistorico = 0;
   var $v30_valorcanceladohistorico = 0;
   var $v30_instituicao = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 v30_sequencial = int4 = Código da Evolução da Dívida
                 v30_receita = int4 = Receita do Débito
                 v30_datageracao = date = Data Geração
                 v30_valorhistorico = float8 = Valor Histório
                 v30_valorcorrecao = float8 = Valor Corrigido
                 v30_valorpagoparcialhistorico = float8 = Pagamento Parcial Histórico
                 v30_valorpagoparcial = float8 = Pagamento Parcial
                 v30_valorpago = float8 = Valor Pago
                 v30_valorcancelado = float8 = Valor Cancelado
                 v30_valordesconto = float8 = Valor Desconto
                 v30_valorpagohistorico = float8 = Valor Pago Histórico
                 v30_valorcanceladohistorico = float8 = Valor Cancelado Histórico
                 v30_instituicao = int4 = Instituição
                 ";
   //funcao construtor da classe
   function cl_evolucaodividaativa() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("evolucaodividaativa");
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
       $this->v30_sequencial = ($this->v30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_sequencial"]:$this->v30_sequencial);
       $this->v30_receita = ($this->v30_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_receita"]:$this->v30_receita);
       if($this->v30_datageracao == ""){
         $this->v30_datageracao_dia = ($this->v30_datageracao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_datageracao_dia"]:$this->v30_datageracao_dia);
         $this->v30_datageracao_mes = ($this->v30_datageracao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_datageracao_mes"]:$this->v30_datageracao_mes);
         $this->v30_datageracao_ano = ($this->v30_datageracao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_datageracao_ano"]:$this->v30_datageracao_ano);
         if($this->v30_datageracao_dia != ""){
            $this->v30_datageracao = $this->v30_datageracao_ano."-".$this->v30_datageracao_mes."-".$this->v30_datageracao_dia;
         }
       }
       $this->v30_valorhistorico = ($this->v30_valorhistorico == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_valorhistorico"]:$this->v30_valorhistorico);
       $this->v30_valorcorrecao = ($this->v30_valorcorrecao == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_valorcorrecao"]:$this->v30_valorcorrecao);
       $this->v30_valorpagoparcialhistorico = ($this->v30_valorpagoparcialhistorico == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_valorpagoparcialhistorico"]:$this->v30_valorpagoparcialhistorico);
       $this->v30_valorpagoparcial = ($this->v30_valorpagoparcial == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_valorpagoparcial"]:$this->v30_valorpagoparcial);
       $this->v30_valorpago = ($this->v30_valorpago == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_valorpago"]:$this->v30_valorpago);
       $this->v30_valorcancelado = ($this->v30_valorcancelado == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_valorcancelado"]:$this->v30_valorcancelado);
       $this->v30_valordesconto = ($this->v30_valordesconto == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_valordesconto"]:$this->v30_valordesconto);
       $this->v30_valorpagohistorico = ($this->v30_valorpagohistorico == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_valorpagohistorico"]:$this->v30_valorpagohistorico);
       $this->v30_valorcanceladohistorico = ($this->v30_valorcanceladohistorico == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_valorcanceladohistorico"]:$this->v30_valorcanceladohistorico);
       $this->v30_instituicao = ($this->v30_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_instituicao"]:$this->v30_instituicao);
     }else{
       $this->v30_sequencial = ($this->v30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v30_sequencial"]:$this->v30_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($v30_sequencial){
      $this->atualizacampos();
     if($this->v30_receita == null ){
       $this->erro_sql = " Campo Receita do Débito não informado.";
       $this->erro_campo = "v30_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v30_datageracao == null ){
       $this->erro_sql = " Campo Data Geração não informado.";
       $this->erro_campo = "v30_datageracao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v30_valorhistorico == null ){
       $this->v30_valorhistorico = "0";
     }
     if($this->v30_valorcorrecao == null ){
       $this->v30_valorcorrecao = "0";
     }
     if($this->v30_valorpagoparcialhistorico == null ){
       $this->v30_valorpagoparcialhistorico = "0";
     }
     if($this->v30_valorpagoparcial == null ){
       $this->v30_valorpagoparcial = "0";
     }
     if($this->v30_valorpago == null ){
       $this->v30_valorpago = "0";
     }
     if($this->v30_valorcancelado == null ){
       $this->v30_valorcancelado = "0";
     }
     if($this->v30_valordesconto == null ){
       $this->v30_valordesconto = "0";
     }
     if($this->v30_valorpagohistorico == null ){
       $this->v30_valorpagohistorico = "0";
     }
     if($this->v30_valorcanceladohistorico == null ){
       $this->v30_valorcanceladohistorico = "0";
     }
     if($this->v30_instituicao == null ){
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "v30_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v30_sequencial == "" || $v30_sequencial == null ){
       $result = db_query("select nextval('evolucaodividaativa_v30_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: evolucaodividaativa_v30_sequencial_seq do campo: v30_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->v30_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from evolucaodividaativa_v30_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v30_sequencial)){
         $this->erro_sql = " Campo v30_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v30_sequencial = $v30_sequencial;
       }
     }
     if(($this->v30_sequencial == null) || ($this->v30_sequencial == "") ){
       $this->erro_sql = " Campo v30_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into evolucaodividaativa(
                                       v30_sequencial
                                      ,v30_receita
                                      ,v30_datageracao
                                      ,v30_valorhistorico
                                      ,v30_valorcorrecao
                                      ,v30_valorpagoparcialhistorico
                                      ,v30_valorpagoparcial
                                      ,v30_valorpago
                                      ,v30_valorcancelado
                                      ,v30_valordesconto
                                      ,v30_valorpagohistorico
                                      ,v30_valorcanceladohistorico
                                      ,v30_instituicao
                       )
                values (
                                $this->v30_sequencial
                               ,$this->v30_receita
                               ,".($this->v30_datageracao == "null" || $this->v30_datageracao == ""?"null":"'".$this->v30_datageracao."'")."
                               ,$this->v30_valorhistorico
                               ,$this->v30_valorcorrecao
                               ,$this->v30_valorpagoparcialhistorico
                               ,$this->v30_valorpagoparcial
                               ,$this->v30_valorpago
                               ,$this->v30_valorcancelado
                               ,$this->v30_valordesconto
                               ,$this->v30_valorpagohistorico
                               ,$this->v30_valorcanceladohistorico
                               ,$this->v30_instituicao
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Evolução Dívida Ativa ($this->v30_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Evolução Dívida Ativa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Evolução Dívida Ativa ($this->v30_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v30_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v30_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21151,'$this->v30_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3808,21151,'','".AddSlashes(pg_result($resaco,0,'v30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3808,21147,'','".AddSlashes(pg_result($resaco,0,'v30_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3808,21152,'','".AddSlashes(pg_result($resaco,0,'v30_datageracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3808,21172,'','".AddSlashes(pg_result($resaco,0,'v30_valorhistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3808,21148,'','".AddSlashes(pg_result($resaco,0,'v30_valorcorrecao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3808,21149,'','".AddSlashes(pg_result($resaco,0,'v30_valorpagoparcialhistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3808,21150,'','".AddSlashes(pg_result($resaco,0,'v30_valorpagoparcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3808,21184,'','".AddSlashes(pg_result($resaco,0,'v30_valorpago'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3808,21185,'','".AddSlashes(pg_result($resaco,0,'v30_valorcancelado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3808,21186,'','".AddSlashes(pg_result($resaco,0,'v30_valordesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3808,21187,'','".AddSlashes(pg_result($resaco,0,'v30_valorpagohistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3808,21188,'','".AddSlashes(pg_result($resaco,0,'v30_valorcanceladohistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3808,21153,'','".AddSlashes(pg_result($resaco,0,'v30_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($v30_sequencial=null) {
      $this->atualizacampos();
     $sql = " update evolucaodividaativa set ";
     $virgula = "";
     if(trim($this->v30_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_sequencial"])){
       $sql  .= $virgula." v30_sequencial = $this->v30_sequencial ";
       $virgula = ",";
       if(trim($this->v30_sequencial) == null ){
         $this->erro_sql = " Campo Código da Evolução da Dívida não informado.";
         $this->erro_campo = "v30_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v30_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_receita"])){
       $sql  .= $virgula." v30_receita = $this->v30_receita ";
       $virgula = ",";
       if(trim($this->v30_receita) == null ){
         $this->erro_sql = " Campo Receita do Débito não informado.";
         $this->erro_campo = "v30_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v30_datageracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_datageracao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v30_datageracao_dia"] !="") ){
       $sql  .= $virgula." v30_datageracao = '$this->v30_datageracao' ";
       $virgula = ",";
       if(trim($this->v30_datageracao) == null ){
         $this->erro_sql = " Campo Data Geração não informado.";
         $this->erro_campo = "v30_datageracao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["v30_datageracao_dia"])){
         $sql  .= $virgula." v30_datageracao = null ";
         $virgula = ",";
         if(trim($this->v30_datageracao) == null ){
           $this->erro_sql = " Campo Data Geração não informado.";
           $this->erro_campo = "v30_datageracao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v30_valorhistorico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_valorhistorico"])){
        if(trim($this->v30_valorhistorico)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v30_valorhistorico"])){
           $this->v30_valorhistorico = "0" ;
        }
       $sql  .= $virgula." v30_valorhistorico = $this->v30_valorhistorico ";
       $virgula = ",";
     }
     if(trim($this->v30_valorcorrecao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_valorcorrecao"])){
        if(trim($this->v30_valorcorrecao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v30_valorcorrecao"])){
           $this->v30_valorcorrecao = "0" ;
        }
       $sql  .= $virgula." v30_valorcorrecao = $this->v30_valorcorrecao ";
       $virgula = ",";
     }
     if(trim($this->v30_valorpagoparcialhistorico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_valorpagoparcialhistorico"])){
        if(trim($this->v30_valorpagoparcialhistorico)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v30_valorpagoparcialhistorico"])){
           $this->v30_valorpagoparcialhistorico = "0" ;
        }
       $sql  .= $virgula." v30_valorpagoparcialhistorico = $this->v30_valorpagoparcialhistorico ";
       $virgula = ",";
     }
     if(trim($this->v30_valorpagoparcial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_valorpagoparcial"])){
        if(trim($this->v30_valorpagoparcial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v30_valorpagoparcial"])){
           $this->v30_valorpagoparcial = "0" ;
        }
       $sql  .= $virgula." v30_valorpagoparcial = $this->v30_valorpagoparcial ";
       $virgula = ",";
     }
     if(trim($this->v30_valorpago)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_valorpago"])){
        if(trim($this->v30_valorpago)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v30_valorpago"])){
           $this->v30_valorpago = "0" ;
        }
       $sql  .= $virgula." v30_valorpago = $this->v30_valorpago ";
       $virgula = ",";
     }
     if(trim($this->v30_valorcancelado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_valorcancelado"])){
        if(trim($this->v30_valorcancelado)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v30_valorcancelado"])){
           $this->v30_valorcancelado = "0" ;
        }
       $sql  .= $virgula." v30_valorcancelado = $this->v30_valorcancelado ";
       $virgula = ",";
     }
     if(trim($this->v30_valordesconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_valordesconto"])){
        if(trim($this->v30_valordesconto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v30_valordesconto"])){
           $this->v30_valordesconto = "0" ;
        }
       $sql  .= $virgula." v30_valordesconto = $this->v30_valordesconto ";
       $virgula = ",";
     }
     if(trim($this->v30_valorpagohistorico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_valorpagohistorico"])){
        if(trim($this->v30_valorpagohistorico)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v30_valorpagohistorico"])){
           $this->v30_valorpagohistorico = "0" ;
        }
       $sql  .= $virgula." v30_valorpagohistorico = $this->v30_valorpagohistorico ";
       $virgula = ",";
     }
     if(trim($this->v30_valorcanceladohistorico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_valorcanceladohistorico"])){
        if(trim($this->v30_valorcanceladohistorico)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v30_valorcanceladohistorico"])){
           $this->v30_valorcanceladohistorico = "0" ;
        }
       $sql  .= $virgula." v30_valorcanceladohistorico = $this->v30_valorcanceladohistorico ";
       $virgula = ",";
     }
     if(trim($this->v30_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v30_instituicao"])){
       $sql  .= $virgula." v30_instituicao = $this->v30_instituicao ";
       $virgula = ",";
       if(trim($this->v30_instituicao) == null ){
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "v30_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v30_sequencial!=null){
       $sql .= " v30_sequencial = $this->v30_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v30_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21151,'$this->v30_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_sequencial"]) || $this->v30_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3808,21151,'".AddSlashes(pg_result($resaco,$conresaco,'v30_sequencial'))."','$this->v30_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_receita"]) || $this->v30_receita != "")
             $resac = db_query("insert into db_acount values($acount,3808,21147,'".AddSlashes(pg_result($resaco,$conresaco,'v30_receita'))."','$this->v30_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_datageracao"]) || $this->v30_datageracao != "")
             $resac = db_query("insert into db_acount values($acount,3808,21152,'".AddSlashes(pg_result($resaco,$conresaco,'v30_datageracao'))."','$this->v30_datageracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_valorhistorico"]) || $this->v30_valorhistorico != "")
             $resac = db_query("insert into db_acount values($acount,3808,21172,'".AddSlashes(pg_result($resaco,$conresaco,'v30_valorhistorico'))."','$this->v30_valorhistorico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_valorcorrecao"]) || $this->v30_valorcorrecao != "")
             $resac = db_query("insert into db_acount values($acount,3808,21148,'".AddSlashes(pg_result($resaco,$conresaco,'v30_valorcorrecao'))."','$this->v30_valorcorrecao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_valorpagoparcialhistorico"]) || $this->v30_valorpagoparcialhistorico != "")
             $resac = db_query("insert into db_acount values($acount,3808,21149,'".AddSlashes(pg_result($resaco,$conresaco,'v30_valorpagoparcialhistorico'))."','$this->v30_valorpagoparcialhistorico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_valorpagoparcial"]) || $this->v30_valorpagoparcial != "")
             $resac = db_query("insert into db_acount values($acount,3808,21150,'".AddSlashes(pg_result($resaco,$conresaco,'v30_valorpagoparcial'))."','$this->v30_valorpagoparcial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_valorpago"]) || $this->v30_valorpago != "")
             $resac = db_query("insert into db_acount values($acount,3808,21184,'".AddSlashes(pg_result($resaco,$conresaco,'v30_valorpago'))."','$this->v30_valorpago',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_valorcancelado"]) || $this->v30_valorcancelado != "")
             $resac = db_query("insert into db_acount values($acount,3808,21185,'".AddSlashes(pg_result($resaco,$conresaco,'v30_valorcancelado'))."','$this->v30_valorcancelado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_valordesconto"]) || $this->v30_valordesconto != "")
             $resac = db_query("insert into db_acount values($acount,3808,21186,'".AddSlashes(pg_result($resaco,$conresaco,'v30_valordesconto'))."','$this->v30_valordesconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_valorpagohistorico"]) || $this->v30_valorpagohistorico != "")
             $resac = db_query("insert into db_acount values($acount,3808,21187,'".AddSlashes(pg_result($resaco,$conresaco,'v30_valorpagohistorico'))."','$this->v30_valorpagohistorico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_valorcanceladohistorico"]) || $this->v30_valorcanceladohistorico != "")
             $resac = db_query("insert into db_acount values($acount,3808,21188,'".AddSlashes(pg_result($resaco,$conresaco,'v30_valorcanceladohistorico'))."','$this->v30_valorcanceladohistorico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v30_instituicao"]) || $this->v30_instituicao != "")
             $resac = db_query("insert into db_acount values($acount,3808,21153,'".AddSlashes(pg_result($resaco,$conresaco,'v30_instituicao'))."','$this->v30_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Evolução Dívida Ativa não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v30_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Evolução Dívida Ativa não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($v30_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($v30_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21151,'$v30_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3808,21151,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3808,21147,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3808,21152,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_datageracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3808,21172,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_valorhistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3808,21148,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_valorcorrecao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3808,21149,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_valorpagoparcialhistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3808,21150,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_valorpagoparcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3808,21184,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_valorpago'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3808,21185,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_valorcancelado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3808,21186,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_valordesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3808,21187,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_valorpagohistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3808,21188,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_valorcanceladohistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3808,21153,'','".AddSlashes(pg_result($resaco,$iresaco,'v30_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from evolucaodividaativa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($v30_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " v30_sequencial = $v30_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Evolução Dívida Ativa não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v30_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Evolução Dívida Ativa não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v30_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:evolucaodividaativa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($v30_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from evolucaodividaativa ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = evolucaodividaativa.v30_receita";
     $sql .= "      inner join db_config  on  db_config.codigo = evolucaodividaativa.v30_instituicao";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join tabrectipo  on  tabrectipo.k116_sequencial = tabrec.k02_tabrectipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v30_sequencial)) {
         $sql2 .= " where evolucaodividaativa.v30_sequencial = $v30_sequencial ";
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
   public function sql_query_file ($v30_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from evolucaodividaativa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v30_sequencial)){
         $sql2 .= " where evolucaodividaativa.v30_sequencial = $v30_sequencial ";
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

  /**
   * Valida se já foi processada a data informada
   * @param  DBDate $oDataGeracao   data de geração a ser verificada
   * @return string
   */
  public function verificaDataProcessada ( DBDate $oDataGeracao ) {

    $sSqlVerificaDataProcessada  = "select v30_receita                                   ";
    $sSqlVerificaDataProcessada .= "  from evolucaodividaativa                           ";
    $sSqlVerificaDataProcessada .= " where v30_datageracao = '{$oDataGeracao->getDate()}'";

    return $sSqlVerificaDataProcessada;
}

  /**
   * Busca Instituições que possuam registro na arreinstit
   * @return string
   */
  public function getInstituicoes() {

    $sSqlInstituicoes = "select codigo from db_config where exists (select 1 from arreinstit where k00_instit = codigo limit 1) ";
    return $sSqlInstituicoes;
  }

  /**
   * Busca valores valores por conta e receita
   * @param  DBDate $oDataInicio - Periodo inicial
   * @param  DBDate $oDataFinal  - Periodo final
   * @param  string $sCampos
   * @param  string $sOrdem
   * @return string
   */
  public function getValoresPorPeriodoContaPcasp ( DBDate $oDataInicio, DBDate $oDataFinal, $sCampos = '*', $sOrdem = 'conplano.c60_codcon, v30_receita, v30_datageracao' ){

    $iInstituicao = db_getsession('DB_instit');
    $iAnousu      = db_getsession('DB_anousu');

    $sSqlValoresPorPeriodo  = "select {$sCampos}                                                                                                ";
    $sSqlValoresPorPeriodo .= "  from evolucaodividaativa                                                                                       ";
    $sSqlValoresPorPeriodo .= "       inner join db_config                 on db_config.codigo             = evolucaodividaativa.v30_instituicao";
    $sSqlValoresPorPeriodo .= "       inner join tabrec                    on tabrec.k02_codigo            = evolucaodividaativa.v30_receita    ";
    $sSqlValoresPorPeriodo .= "       inner join taborc                    on tabrec.k02_codigo            = taborc.k02_codigo                  ";
    $sSqlValoresPorPeriodo .= "       inner join orcreceita                on k02_anousu                   = o70_anousu                         ";
    $sSqlValoresPorPeriodo .= "                                           and taborc.k02_codrec            = o70_codrec                         ";
    $sSqlValoresPorPeriodo .= "                                           and db_config.codigo             = o70_instit                         ";
    $sSqlValoresPorPeriodo .= "       inner join conplanoorcamento         on conplanoorcamento.c60_codcon = o70_codfon                         ";
    $sSqlValoresPorPeriodo .= "                                           and conplanoorcamento.c60_anousu = o70_anousu                         ";
    $sSqlValoresPorPeriodo .= "       inner join conplanoconplanoorcamento on c72_conplanoorcamento        = conplanoorcamento.c60_codcon       ";
    $sSqlValoresPorPeriodo .= "                                           and c72_anousu                   = conplanoorcamento.c60_anousu       ";
    $sSqlValoresPorPeriodo .= "       inner join conplano                  on c72_conplano                 = conplano.c60_codcon                ";
    $sSqlValoresPorPeriodo .= "                                           and conplanoorcamento.c60_anousu = conplano.c60_anousu                ";
    $sSqlValoresPorPeriodo .= "       inner join tabrecjm                  on tabrecjm.k02_codjm           = tabrec.k02_codjm                   ";
    $sSqlValoresPorPeriodo .= "       inner join tabrectipo                on tabrectipo.k116_sequencial   = tabrec.k02_tabrectipo              ";
    $sSqlValoresPorPeriodo .= " where v30_datageracao between '{$oDataInicio->getDate()}' and '{$oDataFinal->getDate()}'                        ";
    $sSqlValoresPorPeriodo .= "   and v30_instituicao = {$iInstituicao}                                                                         ";
    $sSqlValoresPorPeriodo .= "   and o70_anousu      = {$iAnousu}                                                                              ";
    $sSqlValoresPorPeriodo .= "   and conplano.c60_codcon      = 500966                                                                         ";
    $sSqlValoresPorPeriodo .= " order by {$sOrdem}                                                                                              ";

    return $sSqlValoresPorPeriodo;
  }

  /**
   * Busca valores valores por codrec e receita
   * @param  DBDate $oDataInicio - Periodo inicial
   * @param  DBDate $oDataFinal  - Periodo final
   * @param  string $sCampos
   * @param  string $sOrdem
   * @return string
   */
  public function getValoresPorPeriodoReceitaOrcamentaria ( DBDate $oDataInicio, DBDate $oDataFinal, $sCampos = '*', $sOrdem = "orcreceita.o70_codrec, v30_receita, v30_datageracao" ){

    $iInstituicao = db_getsession('DB_instit');
    $iAnousu      = db_getsession('DB_anousu');

    $sSqlValoresPorPeriodo  = "select {$sCampos}                                                                                          ";
    $sSqlValoresPorPeriodo .= "  from evolucaodividaativa                                                                                 ";
    $sSqlValoresPorPeriodo .= "       inner join tabrec                    on v30_receita                  = tabrec.k02_codigo            ";
    $sSqlValoresPorPeriodo .= "       inner join taborc                    on tabrec.k02_codigo            = taborc.k02_codigo            ";
    $sSqlValoresPorPeriodo .= "       inner join orcreceita                on k02_anousu                   = o70_anousu                   ";
    $sSqlValoresPorPeriodo .= "                                           and taborc.k02_codrec            = o70_codrec                   ";
    $sSqlValoresPorPeriodo .= "       inner join orcfontes                 on o70_codfon                   = o57_codfon                   ";
    $sSqlValoresPorPeriodo .= "                                           and o70_anousu                   = o57_anousu                   ";
    $sSqlValoresPorPeriodo .= "       inner join conplanoorcamento         on conplanoorcamento.c60_codcon = o70_codfon                   ";
    $sSqlValoresPorPeriodo .= "                                           and conplanoorcamento.c60_anousu = o70_anousu                   ";
    $sSqlValoresPorPeriodo .= "       inner join conplanoconplanoorcamento on c72_conplanoorcamento        = conplanoorcamento.c60_codcon ";
    $sSqlValoresPorPeriodo .= "                                           and c72_anousu                   = conplanoorcamento.c60_anousu ";
    $sSqlValoresPorPeriodo .= "       inner join conplano                  on c72_conplano                 = conplano.c60_codcon          ";
    $sSqlValoresPorPeriodo .= "                                           and conplanoorcamento.c60_anousu = conplano.c60_anousu          ";
    $sSqlValoresPorPeriodo .= " where v30_datageracao  between '{$oDataInicio->getDate()}' and '{$oDataFinal->getDate()}'                 ";
    $sSqlValoresPorPeriodo .= "   and v30_instituicao = {$iInstituicao}                                                                   ";
    $sSqlValoresPorPeriodo .= "   and o70_anousu      = {$iAnousu}                                                                        ";
    $sSqlValoresPorPeriodo .= " order by {$sOrdem}                                                                                        ";

    return $sSqlValoresPorPeriodo;
  }

  /**
   * Montamos a query responsável por buscar os valores pagos por receita
   * @param  int $iCadTipoDebito tipo de débito
   * @param  int $iInstituicao   Instituição
   * @return string
   */
  /*
  public function getSql($sNome="", $iInstituicao) {

     $iCadTipoDebito = '5,6,8,19,34';

     switch ($sNome) {
       case 'PorReceita':
         return $this->getValoresPagamentosPorReceita($iCadTipoDebito, $iInstituicao);
         break;

       default:
         # code...
         break;
     }

  }*/


  public function getValoresPagamentosPorReceita($iCadTipoDebito, $iInstituicao) {

    $sSqlPagamentos  = " select receita_arrepaga as receita,                                                 ";
    $sSqlPagamentos .= "        sum(valor_pagamento)::float8 as valor_pagamento,                             ";
    $sSqlPagamentos .= "        sum(valor_pagamento_historico)::float8 as valor_pagamento_historico          ";
    $sSqlPagamentos .= "   from (select arrepaga.k00_receit as receita_arrepaga,                             ";
    $sSqlPagamentos .= "                round(sum(arrepaga.k00_valor), 2) as valor_pagamento,                ";
    $sSqlPagamentos .= "                case when arrecant.k00_receit = arrepaga.k00_receit then             ";
    $sSqlPagamentos .= "                     round(sum(arrecant.k00_valor), 2)                               ";
    $sSqlPagamentos .= "                end as valor_pagamento_historico                                     ";
    $sSqlPagamentos .= "           from arrepaga                                                             ";
    $sSqlPagamentos .= "                inner join arrecant   on arrecant.k00_numpre   = arrepaga.k00_numpre ";
    $sSqlPagamentos .= "                                     and arrecant.k00_numpar   = arrepaga.k00_numpar ";
    $sSqlPagamentos .= "                inner join arretipo   on arrecant.k00_tipo     = arretipo.k00_tipo   ";
    $sSqlPagamentos .= "                inner join cadtipo    on arretipo.k03_tipo     = cadtipo.k03_tipo    ";
    $sSqlPagamentos .= "                inner join arreinstit on arreinstit.k00_numpre = arrepaga.k00_numpre ";
    $sSqlPagamentos .= "          where cadtipo.k03_tipo      in ({$iCadTipoDebito})                            ";
    $sSqlPagamentos .= "            and arreinstit.k00_instit = {$iInstituicao}                              ";
    $sSqlPagamentos .= "            and arrepaga.k00_hist     not in ( 918, 970 )                            ";
    $sSqlPagamentos .= "         group by arrepaga.k00_receit, arrecant.k00_receit) as x                     ";
    $sSqlPagamentos .= "  group by receita_arrepaga;                                                         ";

    return $sSqlPagamentos;
  }

  /**
   * Montamos a query responsável por buscar os valores dos pagamentos parciais por receita
   * @param  int $iCadTipoDebito tipo de débito
   * @param  int $iInstituicao   Instituição
   * @return string
   */
  public function getValoresPagamentosParciaisPorReceita( $iCadTipoDebito, $iInstituicao ) {

    $sSqlPagamentosParciais  = " select arreckey.k00_receit as receita,                                               ";
    $sSqlPagamentosParciais .= "        round( sum(abatimentoarreckey.k128_valorabatido + case when abatimentoarreckey.k128_correcao < 0 then abatimentoarreckey.k128_correcao else 0 end ), 2 ) as valor_historico,     ";
    $sSqlPagamentosParciais .= "        round( sum(case when abatimentoarreckey.k128_correcao > 0 then abatimentoarreckey.k128_correcao else 0 end), 2 )     as valor_corrigido      ";
    $sSqlPagamentosParciais .= "   from abatimento                                                                    ";
    $sSqlPagamentosParciais .= "        inner join abatimentoarreckey  on k125_sequencial       = k128_abatimento     ";
    $sSqlPagamentosParciais .= "        inner join arreckey            on k128_arreckey         = k00_sequencial      ";
    $sSqlPagamentosParciais .= "                                      and arreckey.k00_hist not in (918, 970)         ";
    $sSqlPagamentosParciais .= "        inner join tipoabatimento      on k125_tipoabatimento   = k126_sequencial     ";
    $sSqlPagamentosParciais .= "                                      and k126_sequencial       in (1)                ";
    $sSqlPagamentosParciais .= "        inner join arretipo            on arreckey.k00_tipo     = arretipo.k00_tipo   ";
    $sSqlPagamentosParciais .= "                                      and arretipo.k03_tipo     in ({$iCadTipoDebito})   ";
    $sSqlPagamentosParciais .= "        inner join arreinstit          on arreinstit.k00_numpre = arreckey.k00_numpre ";
    $sSqlPagamentosParciais .= "                                      and arreinstit.k00_instit = {$iInstituicao}     ";
    $sSqlPagamentosParciais .= "  group by receita                                                                    ";

    return $sSqlPagamentosParciais;
  }

  /**
   * Montamos a query responsável por buscar os valores de multa dos pagamentos parciais por receita
   * @param  int $iCadTipoDebito tipo de débito
   * @param  int $iInstituicao   Instituição
   * @return string
   */
  public function getValoresMultaPagamentosParciaisPorReceita( $iCadTipoDebito, $iInstituicao ) {

    $sSqlMultaParciais  = " select case when tabrec.k02_recmul is not null or tabrec.k02_recmul <> 0 then        ";
    $sSqlMultaParciais .= "               tabrec.k02_recmul                                                      ";
    $sSqlMultaParciais .= "             else                                                                     ";
    $sSqlMultaParciais .= "               0                                                                      ";
    $sSqlMultaParciais .= "        end as receita,                                                               ";
    $sSqlMultaParciais .= "        round(sum(abatimentoarreckey.k128_multa), 2) as valor_corrigido               ";
    $sSqlMultaParciais .= "   from abatimento                                                                    ";
    $sSqlMultaParciais .= "        inner join abatimentoarreckey  on k125_sequencial       = k128_abatimento     ";
    $sSqlMultaParciais .= "        inner join arreckey            on k128_arreckey         = k00_sequencial      ";
    $sSqlMultaParciais .= "                                      and arreckey.k00_hist not in (918, 970)         ";
    $sSqlMultaParciais .= "        inner join tipoabatimento      on k125_tipoabatimento   = k126_sequencial     ";
    $sSqlMultaParciais .= "                                      and k126_sequencial       in (1)                ";
    $sSqlMultaParciais .= "        inner join arretipo            on arreckey.k00_tipo     = arretipo.k00_tipo   ";
    $sSqlMultaParciais .= "                                      and arretipo.k03_tipo     in ({$iCadTipoDebito})   ";
    $sSqlMultaParciais .= "        inner join tabrec              on tabrec.k02_codigo     = arreckey.k00_receit ";
    $sSqlMultaParciais .= "        inner join arreinstit          on arreinstit.k00_numpre = arreckey.k00_numpre ";
    $sSqlMultaParciais .= "                                      and arreinstit.k00_instit = {$iInstituicao}     ";
    $sSqlMultaParciais .= "  group by receita                                                                    ";

    return $sSqlMultaParciais;
  }

  /**
   * Montamos a query responsável por buscar os valores de juros dos pagamentos parciais por receita
   * @param  int $iCadTipoDebito tipo de débito
   * @param  int $iInstituicao   Instituição
   * @return string
   */
  public function getValoresJurosPagamentosParciaisPorReceita( $iCadTipoDebito, $iInstituicao ) {

    $sSqlJurosParciais  = " select case when tabrec.k02_recjur is not null or tabrec.k02_recjur <> 0 then        ";
    $sSqlJurosParciais .= "               tabrec.k02_recjur                                                      ";
    $sSqlJurosParciais .= "             else                                                                     ";
    $sSqlJurosParciais .= "               0                                                                      ";
    $sSqlJurosParciais .= "        end as receita,                                                               ";
    $sSqlJurosParciais .= "        round(sum(abatimentoarreckey.k128_juros), 2) as valor_corrigido               ";
    $sSqlJurosParciais .= "   from abatimento                                                                    ";
    $sSqlJurosParciais .= "        inner join abatimentoarreckey  on k125_sequencial       = k128_abatimento     ";
    $sSqlJurosParciais .= "        inner join arreckey            on k128_arreckey         = k00_sequencial      ";
    $sSqlJurosParciais .= "                                      and arreckey.k00_hist not in (918, 970)         ";
    $sSqlJurosParciais .= "        inner join tipoabatimento      on k125_tipoabatimento   = k126_sequencial     ";
    $sSqlJurosParciais .= "                                      and k126_sequencial       in (1)                ";
    $sSqlJurosParciais .= "        inner join arretipo            on arreckey.k00_tipo     = arretipo.k00_tipo   ";
    $sSqlJurosParciais .= "                                      and arretipo.k03_tipo     in ({$iCadTipoDebito})   ";
    $sSqlJurosParciais .= "        inner join tabrec              on tabrec.k02_codigo     = arreckey.k00_receit ";
    $sSqlJurosParciais .= "        inner join arreinstit          on arreinstit.k00_numpre = arreckey.k00_numpre ";
    $sSqlJurosParciais .= "                                      and arreinstit.k00_instit = {$iInstituicao}     ";
    $sSqlJurosParciais .= "  group by receita                                                                    ";

    return $sSqlJurosParciais;
  }

  /**
   * Montamos a query responsável por buscar os valores cancelados por receita
   * @param  int $iCadTipoDebito tipo de débito
   * @param  int $iInstituicao   Instituição
   * @return string
   */
  public function getValoresCanceladosPorReceita($iCadTipoDebito, $iInstituicao) {

    $sSqlCancelamentos  = " select arrecant.k00_receit as receita,                                                    ";
    $sSqlCancelamentos .= "        round(sum(cancdebitosprocreg.k24_vlrcor), 2)::float8 as valor_cancelado,           ";
    $sSqlCancelamentos .= "        round(sum(cancdebitosprocreg.k24_vlrhis), 2)::float8 as valor_cancelado_historico  ";
    $sSqlCancelamentos .= "   from arrecant                                                                           ";
    $sSqlCancelamentos .= "        inner join cancdebitosreg      on arrecant.k00_numpre  = cancdebitosreg.k21_numpre ";
    $sSqlCancelamentos .= "                                      and arrecant.k00_numpar  = cancdebitosreg.k21_numpar ";
    $sSqlCancelamentos .= "                                      and arrecant.k00_receit  = cancdebitosreg.k21_receit ";
    $sSqlCancelamentos .= "        inner join cancdebitosprocreg on k24_cancdebitosreg    = k21_sequencia             ";
    $sSqlCancelamentos .= "        inner join arretipo           on arrecant.k00_tipo     = arretipo.k00_tipo         ";
    $sSqlCancelamentos .= "        inner join cadtipo            on arretipo.k03_tipo     = cadtipo.k03_tipo          ";
    $sSqlCancelamentos .= "        inner join arreinstit         on arreinstit.k00_numpre = arrecant.k00_numpre       ";
    $sSqlCancelamentos .= "  where cadtipo.k03_tipo      in ({$iCadTipoDebito})                                          ";
    $sSqlCancelamentos .= "    and arreinstit.k00_instit = {$iInstituicao}                                            ";
    $sSqlCancelamentos .= "    and arrecant.k00_hist     not in ( 918, 970 )                                          ";
    $sSqlCancelamentos .= "  group by arrecant.k00_receit                                                             ";

    return $sSqlCancelamentos;
  }

  /**
   * Montamos a query responsável por buscar os valores cancelados por receita
   * @param  int $iCadTipoDebito tipo de débito
   * @param  int $iInstituicao   Instituição
   * @return string
   */
  public function getValoresMultasCanceladosPorReceita($iCadTipoDebito, $iInstituicao) {

    $sSqlCancelamentosMulta  = "select case when tabrec.k02_recmul is not null OR tabrec.k02_recmul <> 0 then             ";
    $sSqlCancelamentosMulta .= "              tabrec.k02_recmul                                                           ";
    $sSqlCancelamentosMulta .= "            else                                                                          ";
    $sSqlCancelamentosMulta .= "              0                                                                           ";
    $sSqlCancelamentosMulta .= "       end as receita,                                                                    ";
    $sSqlCancelamentosMulta .= "       round(sum(cancdebitosprocreg.k24_multa), 2)::float8 as valor_cancelado             ";
    $sSqlCancelamentosMulta .= "  from arrecant                                                                           ";
    $sSqlCancelamentosMulta .= "       inner join cancdebitosreg      on arrecant.k00_numpre  = cancdebitosreg.k21_numpre ";
    $sSqlCancelamentosMulta .= "                                     and arrecant.k00_numpar  = cancdebitosreg.k21_numpar ";
    $sSqlCancelamentosMulta .= "                                     and arrecant.k00_receit  = cancdebitosreg.k21_receit ";
    $sSqlCancelamentosMulta .= "       inner join cancdebitosprocreg on k24_cancdebitosreg    = k21_sequencia             ";
    $sSqlCancelamentosMulta .= "       inner join arretipo           on arrecant.k00_tipo     = arretipo.k00_tipo         ";
    $sSqlCancelamentosMulta .= "       inner join cadtipo            on arretipo.k03_tipo     = cadtipo.k03_tipo          ";
    $sSqlCancelamentosMulta .= "       inner join arreinstit         on arreinstit.k00_numpre = arrecant.k00_numpre       ";
    $sSqlCancelamentosMulta .= "       inner join tabrec             on k02_codigo            = arrecant.k00_receit       ";
    $sSqlCancelamentosMulta .= " where cadtipo.k03_tipo      in ({$iCadTipoDebito})                                          ";
    $sSqlCancelamentosMulta .= "   and arreinstit.k00_instit = {$iInstituicao}                                            ";
    $sSqlCancelamentosMulta .= "   and arrecant.k00_hist     not in ( 918, 970 )                                          ";
    $sSqlCancelamentosMulta .= " group by receita                                                                         ";

    return $sSqlCancelamentosMulta;
  }

  /**
   * Montamos a query responsável por buscar os valores cancelados por receita
   * @param  int $iCadTipoDebito tipo de débito
   * @param  int $iInstituicao   Instituição
   * @return string
   */
  public function getValoresJurosCanceladosPorReceita( $iCadTipoDebito, $iInstituicao ) {

    $sSqlCancelamentosMulta  = "select case when tabrec.k02_recjur is not null OR tabrec.k02_recjur <> 0 then             ";
    $sSqlCancelamentosMulta .= "              tabrec.k02_recjur                                                           ";
    $sSqlCancelamentosMulta .= "            else                                                                          ";
    $sSqlCancelamentosMulta .= "              0                                                                           ";
    $sSqlCancelamentosMulta .= "       end as receita,                                                                    ";
    $sSqlCancelamentosMulta .= "       round(sum(cancdebitosprocreg.k24_juros), 2)::float8 as valor_cancelado             ";
    $sSqlCancelamentosMulta .= "  from arrecant                                                                           ";
    $sSqlCancelamentosMulta .= "       inner join cancdebitosreg      on arrecant.k00_numpre  = cancdebitosreg.k21_numpre ";
    $sSqlCancelamentosMulta .= "                                     and arrecant.k00_numpar  = cancdebitosreg.k21_numpar ";
    $sSqlCancelamentosMulta .= "                                     and arrecant.k00_receit  = cancdebitosreg.k21_receit ";
    $sSqlCancelamentosMulta .= "       inner join cancdebitosprocreg on k24_cancdebitosreg    = k21_sequencia             ";
    $sSqlCancelamentosMulta .= "       inner join arretipo           on arrecant.k00_tipo     = arretipo.k00_tipo         ";
    $sSqlCancelamentosMulta .= "       inner join cadtipo            on arretipo.k03_tipo     = cadtipo.k03_tipo          ";
    $sSqlCancelamentosMulta .= "       inner join arreinstit         on arreinstit.k00_numpre = arrecant.k00_numpre       ";
    $sSqlCancelamentosMulta .= "       inner join tabrec             on k02_codigo            = arrecant.k00_receit       ";
    $sSqlCancelamentosMulta .= " where cadtipo.k03_tipo      in ({$iCadTipoDebito})                                          ";
    $sSqlCancelamentosMulta .= "   and arreinstit.k00_instit = {$iInstituicao}                                            ";
    $sSqlCancelamentosMulta .= "   and arrecant.k00_hist     not in ( 918, 970 )                                          ";
    $sSqlCancelamentosMulta .= " group by receita                                                                         ";

    return $sSqlCancelamentosMulta;
  }

  /**
   * Montamos a query responsável por buscar os valores prescitros por receita
   * @param  int $iCadTipoDebito tipo de débito
   * @param  int $iInstituicao   Instituição
   * @return string
   */
  public function getValoresPrescritosPorReceita( $iCadTipoDebito, $iInstituicao ) {

    $sSqlPrescritos  = " select arreprescr.k30_receit as receita,                                       ";
    $sSqlPrescritos .= "        round(sum(k30_vlrcorr), 2)::float8 as valor_prescrito,                  ";
    $sSqlPrescritos .= "        round(sum(k30_valor), 2)::float8 as valor_prescrito_historico           ";
    $sSqlPrescritos .= "   from arreprescr                                                              ";
    $sSqlPrescritos .= "        inner join arretipo   on arreprescr.k30_tipo    = arretipo.k00_tipo     ";
    $sSqlPrescritos .= "        inner join cadtipo    on arretipo.k03_tipo      = cadtipo.k03_tipo      ";
    $sSqlPrescritos .= "        inner join arreinstit on arreinstit.k00_numpre  = arreprescr.k30_numpre ";
    $sSqlPrescritos .= "  where cadtipo.k03_tipo      in ({$iCadTipoDebito})                               ";
    $sSqlPrescritos .= "    and arreinstit.k00_instit = {$iInstituicao}                                 ";
    $sSqlPrescritos .= "  group by receita                                                              ";

    return $sSqlPrescritos;
  }

  /**
   * Montamos a query responsável por buscar os valores de juros prescitros por receita
   * @param  int $iCadTipoDebito tipo de débito
   * @param  int $iInstituicao   Instituição
   * @return string
   */
  public function getValoresJurosPrescritosPorReceita( $iCadTipoDebito, $iInstituicao ) {

    $sSqlJurosPrescrito  = " select case when tabrec.k02_recjur is not null OR tabrec.k02_recjur <> 0 then  ";
    $sSqlJurosPrescrito .= "              tabrec.k02_recjur                                                 ";
    $sSqlJurosPrescrito .= "            else                                                                ";
    $sSqlJurosPrescrito .= "              0                                                                 ";
    $sSqlJurosPrescrito .= "        end as receita,                                                         ";
    $sSqlJurosPrescrito .= "        round(sum(k30_vlrjuros), 2)::float8 as valor_prescrito                  ";
    $sSqlJurosPrescrito .= "   from arreprescr                                                              ";
    $sSqlJurosPrescrito .= "        inner join arretipo   on arreprescr.k30_tipo    = arretipo.k00_tipo     ";
    $sSqlJurosPrescrito .= "        inner join cadtipo    on arretipo.k03_tipo      = cadtipo.k03_tipo      ";
    $sSqlJurosPrescrito .= "        inner join arreinstit on arreinstit.k00_numpre  = arreprescr.k30_numpre ";
    $sSqlJurosPrescrito .= "        inner join tabrec     on k02_codigo             = arreprescr.k30_receit ";
    $sSqlJurosPrescrito .= "  where cadtipo.k03_tipo      in ({$iCadTipoDebito})                               ";
    $sSqlJurosPrescrito .= "    and arreinstit.k00_instit = {$iInstituicao}                                 ";
    $sSqlJurosPrescrito .= "  group by receita                                                              ";

    return $sSqlJurosPrescrito;
  }

  /**
   * Montamos a query responsável por buscar os valores de multa prescitros por receita
   * @param  int $iCadTipoDebito tipo de débito
   * @param  int $iInstituicao   Instituição
   * @return string
   */
  public function getValoresMultaPrescritosPorReceita( $iCadTipoDebito, $iInstituicao ) {

    $sSqlJurosPrescrito  = " select case when tabrec.k02_recmul is not null OR tabrec.k02_recmul <> 0 then  ";
    $sSqlJurosPrescrito .= "              tabrec.k02_recmul                                                 ";
    $sSqlJurosPrescrito .= "            else                                                                ";
    $sSqlJurosPrescrito .= "              0                                                                 ";
    $sSqlJurosPrescrito .= "        end as receita,                                                         ";
    $sSqlJurosPrescrito .= "        round(sum(k30_multa), 2)::float8 as valor_prescrito                     ";
    $sSqlJurosPrescrito .= "   from arreprescr                                                              ";
    $sSqlJurosPrescrito .= "        inner join arretipo   on arreprescr.k30_tipo    = arretipo.k00_tipo     ";
    $sSqlJurosPrescrito .= "        inner join cadtipo    on arretipo.k03_tipo      = cadtipo.k03_tipo      ";
    $sSqlJurosPrescrito .= "        inner join arreinstit on arreinstit.k00_numpre  = arreprescr.k30_numpre ";
    $sSqlJurosPrescrito .= "        inner join tabrec     on k02_codigo             = arreprescr.k30_receit ";
    $sSqlJurosPrescrito .= "  where cadtipo.k03_tipo      in ({$iCadTipoDebito})                               ";
    $sSqlJurosPrescrito .= "    and arreinstit.k00_instit = {$iInstituicao}                                 ";
    $sSqlJurosPrescrito .= "  group by receita                                                              ";

    return $sSqlJurosPrescrito;
  }

  /**
   * Montamos a query responsável por buscar os valores de descontos por receita
   * @param  int $iCadTipoDebito tipo de débito
   * @param  int $iInstituicao   Instituição
   * @return string
   */
  public function getValoresDescontoPorReceita( $iCadTipoDebito, $iInstituicao ) {

    $sSqlDescontos  = "select arrepaga.k00_receit as receita,                                      ";
    $sSqlDescontos .= "       round(sum(arrepaga.k00_valor), 2)::float8 as valor_desconto          ";
    $sSqlDescontos .= "  from arrepaga                                                             ";
    $sSqlDescontos .= "       inner join arrecant   on arrecant.k00_numpre   = arrepaga.k00_numpre ";
    $sSqlDescontos .= "                            and arrecant.k00_numpar   = arrepaga.k00_numpar ";
    $sSqlDescontos .= "       inner join arretipo   on arrecant.k00_tipo     = arretipo.k00_tipo   ";
    $sSqlDescontos .= "       inner join cadtipo    on arretipo.k03_tipo     = cadtipo.k03_tipo    ";
    $sSqlDescontos .= "       inner join arreinstit on arreinstit.k00_numpre = arrepaga.k00_numpre ";
    $sSqlDescontos .= " where cadtipo.k03_tipo      in ({$iCadTipoDebito})                            ";
    $sSqlDescontos .= "   and arreinstit.k00_instit = {$iInstituicao}                              ";
    $sSqlDescontos .= "   and arrepaga.k00_hist in ( 918, 970 )                                    ";
    $sSqlDescontos .= " group by arrepaga.k00_receit                                               ";

    return $sSqlDescontos;
  }

  /**
   * Montamos a query responsável por buscar os valores abertos por receita
   * @param  int    $iCadTipoDebito tipo de débito
   * @param  int    $iInstituicao   Instituição
   * @param  DBDate $oDataUsu       Data atual
   * @return string
   */
  public function getValoresAbertosPorReceita( $iCadTipoDebito, $iInstituicao, DBDate $oDataUsu ) {

    $sSqlAbertos  = " select arrecad.k00_receit as receita,                                                                                                                      ";
    $sSqlAbertos .= "        round(sum(k00_valor), 2) as valor_historico,                                                                                                        ";
    $sSqlAbertos .= "        round(sum(fc_corre(k00_receit, k00_dtoper, k00_valor, '{$oDataUsu->getDate()}', {$oDataUsu->getAno()}, k00_dtvenc)-k00_valor), 2) as valor_correcao ";
    $sSqlAbertos .= "   from arrecad                                                                                                                                             ";
    $sSqlAbertos .= "        inner join arretipo   on arrecad.k00_tipo      = arretipo.k00_tipo                                                                                  ";
    $sSqlAbertos .= "        inner join cadtipo    on arretipo.k03_tipo     = cadtipo.k03_tipo                                                                                   ";
    $sSqlAbertos .= "        inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre                                                                                 ";
    $sSqlAbertos .= "  where cadtipo.k03_tipo      in ({$iCadTipoDebito})                                                                                                           ";
    $sSqlAbertos .= "    and arreinstit.k00_instit = {$iInstituicao}                                                                                                             ";
    $sSqlAbertos .= "  group by receita                                                                                                                                          ";

    return $sSqlAbertos;
  }

  /**
   * Montamos a query responsável por buscar os valores dos juros abertos por receita
   * @param  int    $iCadTipoDebito tipo de débito
   * @param  int    $iInstituicao   Instituição
   * @param  DBDate $oDataUsu       Data atual
   * @return string
   */
  public function getValoresJurosAbertosPorReceita( $iCadTipoDebito, $iInstituicao, DBDate $oDataUsu ) {



    $sSqlJurosAberto  = " select case when tabrec.k02_recjur is not null then                                                                                  ";
    $sSqlJurosAberto .= "              tabrec.k02_recjur                                                                                                       ";
    $sSqlJurosAberto .= "            else                                                                                                                      ";
    $sSqlJurosAberto .= "              0                                                                                                                       ";
    $sSqlJurosAberto .= "       end as receita,                                                                                                                ";
    $sSqlJurosAberto .= "       round(sum(fc_corre(k00_receit, k00_dtoper, k00_valor, '{$oDataUsu->getDate()}', {$oDataUsu->getAno()}, k00_dtvenc)             ";
    $sSqlJurosAberto .= "        * fc_juros(k00_receit, k00_dtvenc, '{$oDataUsu->getDate()}', k00_dtoper, false, {$oDataUsu->getAno()})), 2) as valor_correcao ";
    $sSqlJurosAberto .= "  from arrecad                                                                                                                        ";
    $sSqlJurosAberto .= "       inner join arretipo   on arrecad.k00_tipo      = arretipo.k00_tipo                                                             ";
    $sSqlJurosAberto .= "       inner join cadtipo    on arretipo.k03_tipo     = cadtipo.k03_tipo                                                              ";
    $sSqlJurosAberto .= "       inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre                                                            ";
    $sSqlJurosAberto .= "       inner join tabrec     on k02_codigo            = k00_receit                                                                    ";
    $sSqlJurosAberto .= " where cadtipo.k03_tipo      in ({$iCadTipoDebito})                                                                                      ";
    $sSqlJurosAberto .= "   and arreinstit.k00_instit = {$iInstituicao}                                                                                        ";
    $sSqlJurosAberto .= " group by receita                                                                                                                     ";

    return $sSqlJurosAberto;
  }

  /**
   * Montamos a query responsável por buscar os valores das multas em aberto por receita
   * @param  int    $iCadTipoDebito tipo de débito
   * @param  int    $iInstituicao   Instituição
   * @param  DBDate $oDataUsu       Data atual
   * @return string
   */
  public function getValoresMultaAbertosPorReceita( $iCadTipoDebito, $iInstituicao, DBDate $oDataUsu ) {

    $sSqlMultaAberto  = " select case when tabrec.k02_recmul is not null then                                                                               ";
    $sSqlMultaAberto .= "               tabrec.k02_recmul                                                                                                   ";
    $sSqlMultaAberto .= "             else                                                                                                                  ";
    $sSqlMultaAberto .= "               0                                                                                                                   ";
    $sSqlMultaAberto .= "        end as receita,                                                                                                            ";
    $sSqlMultaAberto .= "        round(sum(fc_corre(k00_receit, k00_dtoper, k00_valor, '{$oDataUsu->getDate()}', {$oDataUsu->getAno()}, k00_dtvenc)         ";
    $sSqlMultaAberto .= "         * fc_multa(k00_receit, k00_dtvenc, '{$oDataUsu->getDate()}', k00_dtoper, {$oDataUsu->getAno()})), 2) as valor_correcao    ";
    $sSqlMultaAberto .= "   from arrecad                                                                                                                    ";
    $sSqlMultaAberto .= "        inner join arretipo   on arrecad.k00_tipo      = arretipo.k00_tipo                                                         ";
    $sSqlMultaAberto .= "        inner join cadtipo    on arretipo.k03_tipo     = cadtipo.k03_tipo                                                          ";
    $sSqlMultaAberto .= "        inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre                                                        ";
    $sSqlMultaAberto .= "        inner join tabrec     on k02_codigo            = k00_receit                                                                ";
    $sSqlMultaAberto .= "  where cadtipo.k03_tipo      in ({$iCadTipoDebito})                                                                                 ";
    $sSqlMultaAberto .= "    and arreinstit.k00_instit =  {$iInstituicao}                                                                                   ";
    $sSqlMultaAberto .= "  group by receita                                                                                                                 ";

    return $sSqlMultaAberto;
  }

  /**
   * Montamos a query responsável por buscar os valores das suspensões por receita
   * @param  int    $iCadTipoDebito tipo de débito
   * @param  int    $iInstituicao   Instituição
   * @return string
   */
  public function getValoresSuspensosPorReceita( $iCadTipoDebito, $iInstituicao ) {

    $sSqlSuspenso  = " select arresusp.k00_receit as receita,                                       ";
    $sSqlSuspenso .= "        round(sum(k00_vlrcor - k00_valor), 2) as valor_correcao,              ";
    $sSqlSuspenso .= "        round(sum(k00_valor), 2) as valor_historico                           ";
    $sSqlSuspenso .= "   from arresusp                                                              ";
    $sSqlSuspenso .= "        inner join suspensao  on arresusp.k00_suspensao = ar18_sequencial     ";
    $sSqlSuspenso .= "        inner join arretipo   on arresusp.k00_tipo      = arretipo.k00_tipo   ";
    $sSqlSuspenso .= "        inner join cadtipo    on arretipo.k03_tipo      = cadtipo.k03_tipo    ";
    $sSqlSuspenso .= "        inner join arreinstit on arreinstit.k00_numpre  = arresusp.k00_numpre ";
    $sSqlSuspenso .= "  where cadtipo.k03_tipo      in ({$iCadTipoDebito})                          ";
    $sSqlSuspenso .= "    and arreinstit.k00_instit = {$iInstituicao}                               ";
    $sSqlSuspenso .= "    and suspensao.ar18_situacao = 1                                           ";
    $sSqlSuspenso .= "  group by receita                                                            ";

    return $sSqlSuspenso;
  }

  /**
   * Montamos a query responsável por buscar os valores de juros das suspensões por receita
   * @param  int    $iCadTipoDebito tipo de débito
   * @param  int    $iInstituicao   Instituição
   * @return string
   */
  public function getValoresMultaSuspensosPorReceita( $iCadTipoDebito, $iInstituicao ) {

     $sSqlMultaSuspenos  = " select case when tabrec.k02_recmul is not null then                        ";
     $sSqlMultaSuspenos .= "              tabrec.k02_recmul                                             ";
     $sSqlMultaSuspenos .= "            else                                                            ";
     $sSqlMultaSuspenos .= "              0                                                             ";
     $sSqlMultaSuspenos .= "       end as receita,                                                      ";
     $sSqlMultaSuspenos .= "       round(sum(k00_vlrmul), 2) as valor_correcao                          ";
     $sSqlMultaSuspenos .= "  from arresusp                                                             ";
     $sSqlMultaSuspenos .= "       inner join suspensao  on arresusp.k00_suspensao = ar18_sequencial    ";
     $sSqlMultaSuspenos .= "       inner join arretipo   on arresusp.k00_tipo      = arretipo.k00_tipo  ";
     $sSqlMultaSuspenos .= "       inner join cadtipo    on arretipo.k03_tipo     = cadtipo.k03_tipo    ";
     $sSqlMultaSuspenos .= "       inner join arreinstit on arreinstit.k00_numpre = arresusp.k00_numpre ";
     $sSqlMultaSuspenos .= "       inner join tabrec     on k02_codigo            = k00_receit          ";
     $sSqlMultaSuspenos .= " where cadtipo.k03_tipo      in ({$iCadTipoDebito})                            ";
     $sSqlMultaSuspenos .= "   and arreinstit.k00_instit = {$iInstituicao}                              ";
     $sSqlMultaSuspenos .= "   and suspensao.ar18_situacao = 1                                          ";
     $sSqlMultaSuspenos .= " group by receita                                                           ";

    return $sSqlMultaSuspenos;
  }

  /**
   * Montamos a query responsável por buscar os valores de multa das suspensões por receita
   * @param  int    $iCadTipoDebito tipo de débito
   * @param  int    $iInstituicao   Instituição
   * @return string
   */
  public function getValoresJurosSuspensosPorReceita( $iCadTipoDebito, $iInstituicao ) {

    $sSqlJurosSuspensos  = " select case when tabrec.k02_recjur is not null then                         ";
    $sSqlJurosSuspensos .= "               tabrec.k02_recjur                                             ";
    $sSqlJurosSuspensos .= "             else                                                            ";
    $sSqlJurosSuspensos .= "               0                                                             ";
    $sSqlJurosSuspensos .= "        end as receita,                                                      ";
    $sSqlJurosSuspensos .= "        round(sum(k00_vlrjur), 2) as valor_correcao                          ";
    $sSqlJurosSuspensos .= "   from arresusp                                                             ";
    $sSqlJurosSuspensos .= "        inner join suspensao  on arresusp.k00_suspensao = ar18_sequencial    ";
    $sSqlJurosSuspensos .= "        inner join arretipo   on arresusp.k00_tipo     = arretipo.k00_tipo   ";
    $sSqlJurosSuspensos .= "        inner join cadtipo    on arretipo.k03_tipo     = cadtipo.k03_tipo    ";
    $sSqlJurosSuspensos .= "        inner join arreinstit on arreinstit.k00_numpre = arresusp.k00_numpre ";
    $sSqlJurosSuspensos .= "        inner join tabrec     on k02_codigo            = k00_receit          ";
    $sSqlJurosSuspensos .= "  where cadtipo.k03_tipo      in ({$iCadTipoDebito})                            ";
    $sSqlJurosSuspensos .= "    and arreinstit.k00_instit = {$iInstituicao}                              ";
    $sSqlJurosSuspensos .= "    and suspensao.ar18_situacao = 1                                          ";
    $sSqlJurosSuspensos .= "  group by receita                                                           ";

    return $sSqlJurosSuspensos;
  }
}