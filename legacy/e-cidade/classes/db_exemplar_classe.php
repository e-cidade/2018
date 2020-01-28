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
//MODULO: biblioteca
//CLASSE DA ENTIDADE exemplar
class cl_exemplar {
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
   var $bi23_codigo = 0;
   var $bi23_acervo = 0;
   var $bi23_codbarras = 0;
   var $bi23_aquisicao = 0;
   var $bi23_dataaquisicao_dia = null;
   var $bi23_dataaquisicao_mes = null;
   var $bi23_dataaquisicao_ano = null;
   var $bi23_dataaquisicao = null;
   var $bi23_situacao = null;
   var $bi23_exemplar = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 bi23_codigo = int8 = Código
                 bi23_acervo = int8 = Acervo
                 bi23_codbarras = int8 = Cód. Barras
                 bi23_aquisicao = int8 = Tipo de Aquisição
                 bi23_dataaquisicao = date = Data de Aquisição
                 bi23_situacao = char(1) = Situação
                 bi23_exemplar = int4 = Exemplar
                 ";
   //funcao construtor da classe
   function cl_exemplar() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("exemplar");
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
       $this->bi23_codigo = ($this->bi23_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi23_codigo"]:$this->bi23_codigo);
       $this->bi23_acervo = ($this->bi23_acervo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi23_acervo"]:$this->bi23_acervo);
       $this->bi23_codbarras = ($this->bi23_codbarras == ""?@$GLOBALS["HTTP_POST_VARS"]["bi23_codbarras"]:$this->bi23_codbarras);
       $this->bi23_aquisicao = ($this->bi23_aquisicao == ""?@$GLOBALS["HTTP_POST_VARS"]["bi23_aquisicao"]:$this->bi23_aquisicao);
       if($this->bi23_dataaquisicao == ""){
         $this->bi23_dataaquisicao_dia = ($this->bi23_dataaquisicao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi23_dataaquisicao_dia"]:$this->bi23_dataaquisicao_dia);
         $this->bi23_dataaquisicao_mes = ($this->bi23_dataaquisicao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi23_dataaquisicao_mes"]:$this->bi23_dataaquisicao_mes);
         $this->bi23_dataaquisicao_ano = ($this->bi23_dataaquisicao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi23_dataaquisicao_ano"]:$this->bi23_dataaquisicao_ano);
         if($this->bi23_dataaquisicao_dia != ""){
            $this->bi23_dataaquisicao = $this->bi23_dataaquisicao_ano."-".$this->bi23_dataaquisicao_mes."-".$this->bi23_dataaquisicao_dia;
         }
       }
       $this->bi23_situacao = ($this->bi23_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["bi23_situacao"]:$this->bi23_situacao);
       $this->bi23_exemplar = ($this->bi23_exemplar == ""?@$GLOBALS["HTTP_POST_VARS"]["bi23_exemplar"]:$this->bi23_exemplar);
     }else{
       $this->bi23_codigo = ($this->bi23_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi23_codigo"]:$this->bi23_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($bi23_codigo){
      $this->atualizacampos();
     if($this->bi23_acervo == null ){
       $this->erro_sql = " Campo Acervo não informado.";
       $this->erro_campo = "bi23_acervo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi23_codbarras == null ){
       $this->erro_sql = " Campo Cód. Barras não informado.";
       $this->erro_campo = "bi23_codbarras";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi23_aquisicao == null ){
       $this->erro_sql = " Campo Tipo de Aquisição não informado.";
       $this->erro_campo = "bi23_aquisicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi23_dataaquisicao == null ){
       $this->erro_sql = " Campo Data de Aquisição não informado.";
       $this->erro_campo = "bi23_dataaquisicao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi23_situacao == null ){
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "bi23_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi23_exemplar == null ){
       $this->erro_sql = " Campo Exemplar não informado.";
       $this->erro_campo = "bi23_exemplar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($bi23_codigo == "" || $bi23_codigo == null ){
       $result = db_query("select nextval('exemplar_bi23_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: exemplar_bi23_codigo_seq do campo: bi23_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->bi23_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from exemplar_bi23_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $bi23_codigo)){
         $this->erro_sql = " Campo bi23_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->bi23_codigo = $bi23_codigo;
       }
     }
     if(($this->bi23_codigo == null) || ($this->bi23_codigo == "") ){
       $this->erro_sql = " Campo bi23_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into exemplar(
                                       bi23_codigo
                                      ,bi23_acervo
                                      ,bi23_codbarras
                                      ,bi23_aquisicao
                                      ,bi23_dataaquisicao
                                      ,bi23_situacao
                                      ,bi23_exemplar
                       )
                values (
                                $this->bi23_codigo
                               ,$this->bi23_acervo
                               ,$this->bi23_codbarras
                               ,$this->bi23_aquisicao
                               ,".($this->bi23_dataaquisicao == "null" || $this->bi23_dataaquisicao == ""?"null":"'".$this->bi23_dataaquisicao."'")."
                               ,'$this->bi23_situacao'
                               ,$this->bi23_exemplar
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Exemplares dos Acervos ($this->bi23_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Exemplares dos Acervos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Exemplares dos Acervos ($this->bi23_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi23_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->bi23_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008942,'$this->bi23_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010151,1008942,'','".AddSlashes(pg_result($resaco,0,'bi23_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010151,1008943,'','".AddSlashes(pg_result($resaco,0,'bi23_acervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010151,1008951,'','".AddSlashes(pg_result($resaco,0,'bi23_codbarras'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010151,1008952,'','".AddSlashes(pg_result($resaco,0,'bi23_aquisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010151,1008944,'','".AddSlashes(pg_result($resaco,0,'bi23_dataaquisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010151,1008945,'','".AddSlashes(pg_result($resaco,0,'bi23_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010151,21640,'','".AddSlashes(pg_result($resaco,0,'bi23_exemplar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($bi23_codigo=null) {
      $this->atualizacampos();
     $sql = " update exemplar set ";
     $virgula = "";
     if(trim($this->bi23_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi23_codigo"])){
       $sql  .= $virgula." bi23_codigo = $this->bi23_codigo ";
       $virgula = ",";
       if(trim($this->bi23_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "bi23_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi23_acervo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi23_acervo"])){
       $sql  .= $virgula." bi23_acervo = $this->bi23_acervo ";
       $virgula = ",";
       if(trim($this->bi23_acervo) == null ){
         $this->erro_sql = " Campo Acervo não informado.";
         $this->erro_campo = "bi23_acervo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi23_codbarras)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi23_codbarras"])){
       $sql  .= $virgula." bi23_codbarras = $this->bi23_codbarras ";
       $virgula = ",";
       if(trim($this->bi23_codbarras) == null ){
         $this->erro_sql = " Campo Cód. Barras não informado.";
         $this->erro_campo = "bi23_codbarras";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi23_aquisicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi23_aquisicao"])){
       $sql  .= $virgula." bi23_aquisicao = $this->bi23_aquisicao ";
       $virgula = ",";
       if(trim($this->bi23_aquisicao) == null ){
         $this->erro_sql = " Campo Tipo de Aquisição não informado.";
         $this->erro_campo = "bi23_aquisicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi23_dataaquisicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi23_dataaquisicao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi23_dataaquisicao_dia"] !="") ){
       $sql  .= $virgula." bi23_dataaquisicao = '$this->bi23_dataaquisicao' ";
       $virgula = ",";
       if(trim($this->bi23_dataaquisicao) == null ){
         $this->erro_sql = " Campo Data de Aquisição não informado.";
         $this->erro_campo = "bi23_dataaquisicao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi23_dataaquisicao_dia"])){
         $sql  .= $virgula." bi23_dataaquisicao = null ";
         $virgula = ",";
         if(trim($this->bi23_dataaquisicao) == null ){
           $this->erro_sql = " Campo Data de Aquisição não informado.";
           $this->erro_campo = "bi23_dataaquisicao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bi23_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi23_situacao"])){
       $sql  .= $virgula." bi23_situacao = '$this->bi23_situacao' ";
       $virgula = ",";
       if(trim($this->bi23_situacao) == null ){
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "bi23_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi23_exemplar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi23_exemplar"])){
       $sql  .= $virgula." bi23_exemplar = $this->bi23_exemplar ";
       $virgula = ",";
       if(trim($this->bi23_exemplar) == null ){
         $this->erro_sql = " Campo Exemplar não informado.";
         $this->erro_campo = "bi23_exemplar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($bi23_codigo!=null){
       $sql .= " bi23_codigo = $this->bi23_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->bi23_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008942,'$this->bi23_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi23_codigo"]) || $this->bi23_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010151,1008942,'".AddSlashes(pg_result($resaco,$conresaco,'bi23_codigo'))."','$this->bi23_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi23_acervo"]) || $this->bi23_acervo != "")
             $resac = db_query("insert into db_acount values($acount,1010151,1008943,'".AddSlashes(pg_result($resaco,$conresaco,'bi23_acervo'))."','$this->bi23_acervo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi23_codbarras"]) || $this->bi23_codbarras != "")
             $resac = db_query("insert into db_acount values($acount,1010151,1008951,'".AddSlashes(pg_result($resaco,$conresaco,'bi23_codbarras'))."','$this->bi23_codbarras',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi23_aquisicao"]) || $this->bi23_aquisicao != "")
             $resac = db_query("insert into db_acount values($acount,1010151,1008952,'".AddSlashes(pg_result($resaco,$conresaco,'bi23_aquisicao'))."','$this->bi23_aquisicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi23_dataaquisicao"]) || $this->bi23_dataaquisicao != "")
             $resac = db_query("insert into db_acount values($acount,1010151,1008944,'".AddSlashes(pg_result($resaco,$conresaco,'bi23_dataaquisicao'))."','$this->bi23_dataaquisicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi23_situacao"]) || $this->bi23_situacao != "")
             $resac = db_query("insert into db_acount values($acount,1010151,1008945,'".AddSlashes(pg_result($resaco,$conresaco,'bi23_situacao'))."','$this->bi23_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi23_exemplar"]) || $this->bi23_exemplar != "")
             $resac = db_query("insert into db_acount values($acount,1010151,21640,'".AddSlashes(pg_result($resaco,$conresaco,'bi23_exemplar'))."','$this->bi23_exemplar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Exemplares dos Acervos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi23_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Exemplares dos Acervos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi23_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi23_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($bi23_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($bi23_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008942,'$bi23_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010151,1008942,'','".AddSlashes(pg_result($resaco,$iresaco,'bi23_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010151,1008943,'','".AddSlashes(pg_result($resaco,$iresaco,'bi23_acervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010151,1008951,'','".AddSlashes(pg_result($resaco,$iresaco,'bi23_codbarras'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010151,1008952,'','".AddSlashes(pg_result($resaco,$iresaco,'bi23_aquisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010151,1008944,'','".AddSlashes(pg_result($resaco,$iresaco,'bi23_dataaquisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010151,1008945,'','".AddSlashes(pg_result($resaco,$iresaco,'bi23_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010151,21640,'','".AddSlashes(pg_result($resaco,$iresaco,'bi23_exemplar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from exemplar
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($bi23_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " bi23_codigo = $bi23_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Exemplares dos Acervos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi23_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Exemplares dos Acervos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi23_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi23_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:exemplar";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($bi23_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from exemplar ";
     $sql .= "      inner join aquisicao  on  aquisicao.bi04_codigo = exemplar.bi23_aquisicao";
     $sql .= "      inner join acervo  on  acervo.bi06_seq = exemplar.bi23_acervo";
     $sql .= "      left join localacervo  on  localacervo.bi20_acervo = acervo.bi06_seq";
     $sql .= "      left join localizacao  on  localizacao.bi09_codigo = localacervo.bi20_localizacao";
     $sql .= "      left join localexemplar  on  localexemplar.bi27_exemplar = exemplar.bi23_codigo";

     $sql .= "      inner join editora  on  editora.bi02_codigo = acervo.bi06_editora";
     $sql .= "      inner join classiliteraria  on  classiliteraria.bi03_codigo = acervo.bi06_classiliteraria";
     $sql .= "      inner join tipoitem  on  tipoitem.bi05_codigo = acervo.bi06_tipoitem";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = acervo.bi06_biblioteca";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($bi23_codigo)) {
         $sql2 .= " where exemplar.bi23_codigo = $bi23_codigo ";
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
  public function sql_query_file ($bi23_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from exemplar ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($bi23_codigo)){
        $sql2 .= " where exemplar.bi23_codigo = $bi23_codigo ";
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

   function sql_query_etiq3 ( $bi23_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from exemplar ";
     $sql .= "      inner join aquisicao  on  aquisicao.bi04_codigo = exemplar.bi23_aquisicao";
     $sql .= "      inner join acervo  on  acervo.bi06_seq = exemplar.bi23_acervo";
     $sql .= "      inner join localacervo  on  localacervo.bi20_acervo = acervo.bi06_seq";
     $sql .= "      inner join localexemplar  on  localexemplar.bi27_exemplar = exemplar.bi23_codigo";
     $sql .= "      inner join localizacao  on  localizacao.bi09_codigo = localacervo.bi20_localizacao";
     $sql .= "      inner join editora  on  editora.bi02_codigo = acervo.bi06_editora";
     $sql .= "      inner join classiliteraria  on  classiliteraria.bi03_codigo = acervo.bi06_classiliteraria";
     $sql .= "      inner join tipoitem  on  tipoitem.bi05_codigo = acervo.bi06_tipoitem";
     $sql .= "      inner join biblioteca  on  biblioteca.bi17_codigo = acervo.bi06_biblioteca";
     $sql2 = "";
     if($dbwhere==""){
       if($bi23_codigo!=null ){
         $sql2 .= " where exemplar.bi23_codigo = $bi23_codigo ";
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

  public function buscarProximoExemplar ($bi23_acervo) {

    $sql = "select proximo_exemplar($bi23_acervo) ";
    $rs  = db_query($sql);
    if ( !$rs ) {
      return false;
    }
    return db_utils::fieldsMemory($rs, 0)->proximo_exemplar;
  }

  public function sql_query_dados_exemplar($bi23_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from exemplar ";
    $sql .= "  inner join acervo     on  acervo.bi06_seq        = exemplar.bi23_acervo";
    $sql .= "  inner join biblioteca on  acervo.bi06_biblioteca = biblioteca.bi17_codigo";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($bi23_codigo)){
        $sql2 .= " where exemplar.bi23_codigo = $bi23_codigo ";
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