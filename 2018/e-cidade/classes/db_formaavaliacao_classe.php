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

//MODULO: escola
//CLASSE DA ENTIDADE formaavaliacao
class cl_formaavaliacao {
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
   var $ed37_i_codigo = 0;
   var $ed37_c_descr = null;
   var $ed37_c_tipo = null;
   var $ed37_i_menorvalor = 0;
   var $ed37_i_maiorvalor = 0;
   var $ed37_i_variacao = 0;
   var $ed37_c_minimoaprov = null;
   var $ed37_c_parecerarmaz = null;
   var $ed37_i_escola = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed37_i_codigo = int8 = Código
                 ed37_c_descr = char(30) = Descrição
                 ed37_c_tipo = char(10) = Tipo de Resultado
                 ed37_i_menorvalor = float4 = Menor Nota
                 ed37_i_maiorvalor = float4 = Maior Nota
                 ed37_i_variacao = float4 = Variação
                 ed37_c_minimoaprov = char(10) = Mínimo para Aprovação
                 ed37_c_parecerarmaz = char(1) = Parecer Armazenado
                 ed37_i_escola = int8 = Escola
                 ";
   //funcao construtor da classe
   function cl_formaavaliacao() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("formaavaliacao");
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
       $this->ed37_i_codigo = ($this->ed37_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_i_codigo"]:$this->ed37_i_codigo);
       $this->ed37_c_descr = ($this->ed37_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_c_descr"]:$this->ed37_c_descr);
       $this->ed37_c_tipo = ($this->ed37_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_c_tipo"]:$this->ed37_c_tipo);
       $this->ed37_i_menorvalor = ($this->ed37_i_menorvalor == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_i_menorvalor"]:$this->ed37_i_menorvalor);
       $this->ed37_i_maiorvalor = ($this->ed37_i_maiorvalor == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_i_maiorvalor"]:$this->ed37_i_maiorvalor);
       $this->ed37_i_variacao = ($this->ed37_i_variacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_i_variacao"]:$this->ed37_i_variacao);
       $this->ed37_c_minimoaprov = ($this->ed37_c_minimoaprov == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_c_minimoaprov"]:$this->ed37_c_minimoaprov);
       $this->ed37_c_parecerarmaz = ($this->ed37_c_parecerarmaz == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_c_parecerarmaz"]:$this->ed37_c_parecerarmaz);
       $this->ed37_i_escola = ($this->ed37_i_escola === 0 ? @$GLOBALS["HTTP_POST_VARS"]["ed37_i_escola"]:$this->ed37_i_escola);
     }else{
       $this->ed37_i_codigo = ($this->ed37_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed37_i_codigo"]:$this->ed37_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ed37_i_codigo){
      $this->atualizacampos();
     if($this->ed37_c_descr == null ){
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "ed37_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed37_c_tipo == null ){
       $this->erro_sql = " Campo Tipo de Resultado não informado.";
       $this->erro_campo = "ed37_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed37_i_menorvalor == null ){
       $this->erro_sql = " Campo Menor Nota não informado.";
       $this->erro_campo = "ed37_i_menorvalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed37_i_maiorvalor == null ){
       $this->erro_sql = " Campo Maior Nota não informado.";
       $this->erro_campo = "ed37_i_maiorvalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed37_i_variacao == null ){
       $this->erro_sql = " Campo Variação não informado.";
       $this->erro_campo = "ed37_i_variacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed37_c_minimoaprov == null ){
       $this->erro_sql = " Campo Mínimo para Aprovação não informado.";
       $this->erro_campo = "ed37_c_minimoaprov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed37_i_escola == null ){
       $this->ed37_i_escola = "null";
     }
     if($ed37_i_codigo == "" || $ed37_i_codigo == null ){
       $result = db_query("select nextval('formaavaliacao_ed37_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: formaavaliacao_ed37_i_codigo_seq do campo: ed37_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed37_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from formaavaliacao_ed37_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed37_i_codigo)){
         $this->erro_sql = " Campo ed37_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed37_i_codigo = $ed37_i_codigo;
       }
     }
     if(($this->ed37_i_codigo == null) || ($this->ed37_i_codigo == "") ){
       $this->erro_sql = " Campo ed37_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into formaavaliacao(
                                       ed37_i_codigo
                                      ,ed37_c_descr
                                      ,ed37_c_tipo
                                      ,ed37_i_menorvalor
                                      ,ed37_i_maiorvalor
                                      ,ed37_i_variacao
                                      ,ed37_c_minimoaprov
                                      ,ed37_c_parecerarmaz
                                      ,ed37_i_escola
                       )
                values (
                                $this->ed37_i_codigo
                               ,'$this->ed37_c_descr'
                               ,'$this->ed37_c_tipo'
                               ,$this->ed37_i_menorvalor
                               ,$this->ed37_i_maiorvalor
                               ,$this->ed37_i_variacao
                               ,'$this->ed37_c_minimoaprov'
                               ,'$this->ed37_c_parecerarmaz'
                               ,$this->ed37_i_escola
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Formas de Avaliações ($this->ed37_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Formas de Avaliações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Formas de Avaliações ($this->ed37_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed37_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed37_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008420,'$this->ed37_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010071,1008420,'','".AddSlashes(pg_result($resaco,0,'ed37_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008421,'','".AddSlashes(pg_result($resaco,0,'ed37_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008422,'','".AddSlashes(pg_result($resaco,0,'ed37_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008423,'','".AddSlashes(pg_result($resaco,0,'ed37_i_menorvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008424,'','".AddSlashes(pg_result($resaco,0,'ed37_i_maiorvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008425,'','".AddSlashes(pg_result($resaco,0,'ed37_i_variacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008426,'','".AddSlashes(pg_result($resaco,0,'ed37_c_minimoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1008427,'','".AddSlashes(pg_result($resaco,0,'ed37_c_parecerarmaz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010071,1009219,'','".AddSlashes(pg_result($resaco,0,'ed37_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed37_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update formaavaliacao set ";
     $virgula = "";
     if(trim($this->ed37_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_codigo"])){
       $sql  .= $virgula." ed37_i_codigo = $this->ed37_i_codigo ";
       $virgula = ",";
       if(trim($this->ed37_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed37_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_descr"])){
       $sql  .= $virgula." ed37_c_descr = '$this->ed37_c_descr' ";
       $virgula = ",";
       if(trim($this->ed37_c_descr) == null ){
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "ed37_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_tipo"])){
       $sql  .= $virgula." ed37_c_tipo = '$this->ed37_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed37_c_tipo) == null ){
         $this->erro_sql = " Campo Tipo de Resultado não informado.";
         $this->erro_campo = "ed37_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_i_menorvalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_menorvalor"])){
       $sql  .= $virgula." ed37_i_menorvalor = $this->ed37_i_menorvalor ";
       $virgula = ",";
       if(trim($this->ed37_i_menorvalor) == null ){
         $this->erro_sql = " Campo Menor Nota não informado.";
         $this->erro_campo = "ed37_i_menorvalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_i_maiorvalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_maiorvalor"])){
       $sql  .= $virgula." ed37_i_maiorvalor = $this->ed37_i_maiorvalor ";
       $virgula = ",";
       if(trim($this->ed37_i_maiorvalor) == null ){
         $this->erro_sql = " Campo Maior Nota não informado.";
         $this->erro_campo = "ed37_i_maiorvalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_i_variacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_variacao"])){
       $sql  .= $virgula." ed37_i_variacao = $this->ed37_i_variacao ";
       $virgula = ",";
       if(trim($this->ed37_i_variacao) == null ){
         $this->erro_sql = " Campo Variação não informado.";
         $this->erro_campo = "ed37_i_variacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_c_minimoaprov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_minimoaprov"])){
       $sql  .= $virgula." ed37_c_minimoaprov = '$this->ed37_c_minimoaprov' ";
       $virgula = ",";
       if(trim($this->ed37_c_minimoaprov) == null ){
         $this->erro_sql = " Campo Mínimo para Aprovação não informado.";
         $this->erro_campo = "ed37_c_minimoaprov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed37_c_parecerarmaz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_parecerarmaz"])){
       $sql  .= $virgula." ed37_c_parecerarmaz = '$this->ed37_c_parecerarmaz' ";
       $virgula = ",";
     }

     if (trim($this->ed37_i_escola) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_escola"])) {

        if(trim($this->ed37_i_escola) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_escola"])){
           $this->ed37_i_escola = "null" ;
        }
        $sql  .= $virgula." ed37_i_escola = $this->ed37_i_escola ";
        $virgula = ",";
     }
     $sql .= " where ";
     if($ed37_i_codigo!=null){
       $sql .= " ed37_i_codigo = $this->ed37_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed37_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008420,'$this->ed37_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_codigo"]) || $this->ed37_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010071,1008420,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_i_codigo'))."','$this->ed37_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_descr"]) || $this->ed37_c_descr != "")
             $resac = db_query("insert into db_acount values($acount,1010071,1008421,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_c_descr'))."','$this->ed37_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_tipo"]) || $this->ed37_c_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1010071,1008422,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_c_tipo'))."','$this->ed37_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_menorvalor"]) || $this->ed37_i_menorvalor != "")
             $resac = db_query("insert into db_acount values($acount,1010071,1008423,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_i_menorvalor'))."','$this->ed37_i_menorvalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_maiorvalor"]) || $this->ed37_i_maiorvalor != "")
             $resac = db_query("insert into db_acount values($acount,1010071,1008424,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_i_maiorvalor'))."','$this->ed37_i_maiorvalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_variacao"]) || $this->ed37_i_variacao != "")
             $resac = db_query("insert into db_acount values($acount,1010071,1008425,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_i_variacao'))."','$this->ed37_i_variacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_minimoaprov"]) || $this->ed37_c_minimoaprov != "")
             $resac = db_query("insert into db_acount values($acount,1010071,1008426,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_c_minimoaprov'))."','$this->ed37_c_minimoaprov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed37_c_parecerarmaz"]) || $this->ed37_c_parecerarmaz != "")
             $resac = db_query("insert into db_acount values($acount,1010071,1008427,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_c_parecerarmaz'))."','$this->ed37_c_parecerarmaz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed37_i_escola"]) || $this->ed37_i_escola != "")
             $resac = db_query("insert into db_acount values($acount,1010071,1009219,'".AddSlashes(pg_result($resaco,$conresaco,'ed37_i_escola'))."','$this->ed37_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Formas de Avaliações não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed37_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Formas de Avaliações não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed37_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed37_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed37_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed37_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008420,'$ed37_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010071,1008420,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010071,1008421,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010071,1008422,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010071,1008423,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_i_menorvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010071,1008424,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_i_maiorvalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010071,1008425,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_i_variacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010071,1008426,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_c_minimoaprov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010071,1008427,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_c_parecerarmaz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010071,1009219,'','".AddSlashes(pg_result($resaco,$iresaco,'ed37_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from formaavaliacao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed37_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed37_i_codigo = $ed37_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Formas de Avaliações não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed37_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Formas de Avaliações não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed37_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed37_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:formaavaliacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed37_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= " from formaavaliacao ";
     $sql .= "      left join conceito  on  conceito.ed39_i_formaavaliacao = formaavaliacao.ed37_i_codigo";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = formaavaliacao.ed37_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed37_i_codigo)) {
         $sql2 .= " where formaavaliacao.ed37_i_codigo = $ed37_i_codigo ";
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
  public function sql_query_file ($ed37_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos} ";
    $sql .= "  from formaavaliacao ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ed37_i_codigo)){
        $sql2 .= " where formaavaliacao.ed37_i_codigo = $ed37_i_codigo ";
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

  public function sql_formaavaliacao($ed37_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= " from formaavaliacao ";
    $sql .= "      left join conceito  on  conceito.ed39_i_formaavaliacao = formaavaliacao.ed37_i_codigo";
    $sql .= "      left join escola  on  escola.ed18_i_codigo = formaavaliacao.ed37_i_escola";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ed37_i_codigo)) {
        $sql2 .= " where formaavaliacao.ed37_i_codigo = $ed37_i_codigo ";
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
