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

//MODULO: issqn
//CLASSE DA ENTIDADE issvar
class cl_issvar {
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
   var $q05_codigo = 0;
   var $q05_numpre = 0;
   var $q05_numpar = 0;
   var $q05_valor = 0;
   var $q05_ano = 0;
   var $q05_mes = 0;
   var $q05_histor = null;
   var $q05_aliq = 0;
   var $q05_bruto = 0;
   var $q05_vlrinf = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 q05_codigo = int8 = Código
                 q05_numpre = int4 = numpre
                 q05_numpar = int4 = Parcela
                 q05_valor = float8 = valor
                 q05_ano = int4 = ano
                 q05_mes = int4 = mes
                 q05_histor = text = historico
                 q05_aliq = float8 = aliquota
                 q05_bruto = float8 = valor bruto
                 q05_vlrinf = float8 = valor contribuinte
                 ";

   //funcao construtor da classe
   function cl_issvar() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("issvar");
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
       $this->q05_codigo = ($this->q05_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_codigo"]:$this->q05_codigo);
       $this->q05_numpre = ($this->q05_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_numpre"]:$this->q05_numpre);
       $this->q05_numpar = ($this->q05_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_numpar"]:$this->q05_numpar);
       $this->q05_valor = ($this->q05_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_valor"]:$this->q05_valor);
       $this->q05_ano = ($this->q05_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_ano"]:$this->q05_ano);
       $this->q05_mes = ($this->q05_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_mes"]:$this->q05_mes);
       $this->q05_histor = ($this->q05_histor == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_histor"]:$this->q05_histor);
       $this->q05_aliq = ($this->q05_aliq == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_aliq"]:$this->q05_aliq);
       $this->q05_bruto = ($this->q05_bruto == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_bruto"]:$this->q05_bruto);
       $this->q05_vlrinf = ($this->q05_vlrinf == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_vlrinf"]:$this->q05_vlrinf);
     }else{
       $this->q05_codigo = ($this->q05_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["q05_codigo"]:$this->q05_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($q05_codigo){
      $this->atualizacampos();
     if($this->q05_numpre == null ){
       $this->erro_sql = " Campo numpre nao Informado.";
       $this->erro_campo = "q05_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q05_numpar == null ){
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "q05_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q05_valor == null ){
       $this->erro_sql = " Campo valor nao Informado.";
       $this->erro_campo = "q05_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q05_ano == null ){
       $this->erro_sql = " Campo ano nao Informado.";
       $this->erro_campo = "q05_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q05_mes == null ){
       $this->erro_sql = " Campo mes nao Informado.";
       $this->erro_campo = "q05_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q05_aliq == null ){
       $this->erro_sql = " Campo aliquota nao Informado.";
       $this->erro_campo = "q05_aliq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q05_bruto == null ){
       $this->erro_sql = " Campo valor bruto nao Informado.";
       $this->erro_campo = "q05_bruto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q05_vlrinf == null ){
       $this->erro_sql = " Campo valor contribuinte nao Informado.";
       $this->erro_campo = "q05_vlrinf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q05_codigo == "" || $q05_codigo == null ){
       $result = db_query("select nextval('issvar_q05_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: issvar_q05_codigo_seq do campo: q05_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->q05_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from issvar_q05_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $q05_codigo)){
         $this->erro_sql = " Campo q05_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q05_codigo = $q05_codigo;
       }
     }
     if(($this->q05_codigo == null) || ($this->q05_codigo == "") ){
       $this->erro_sql = " Campo q05_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into issvar(
                                       q05_codigo
                                      ,q05_numpre
                                      ,q05_numpar
                                      ,q05_valor
                                      ,q05_ano
                                      ,q05_mes
                                      ,q05_histor
                                      ,q05_aliq
                                      ,q05_bruto
                                      ,q05_vlrinf
                       )
                values (
                                $this->q05_codigo
                               ,$this->q05_numpre
                               ,$this->q05_numpar
                               ,$this->q05_valor
                               ,$this->q05_ano
                               ,$this->q05_mes
                               ,'$this->q05_histor'
                               ,$this->q05_aliq
                               ,$this->q05_bruto
                               ,$this->q05_vlrinf
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q05_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q05_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q05_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q05_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4851,'$this->q05_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,63,4851,'','".AddSlashes(pg_result($resaco,0,'q05_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,63,326,'','".AddSlashes(pg_result($resaco,0,'q05_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,63,327,'','".AddSlashes(pg_result($resaco,0,'q05_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,63,328,'','".AddSlashes(pg_result($resaco,0,'q05_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,63,329,'','".AddSlashes(pg_result($resaco,0,'q05_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,63,330,'','".AddSlashes(pg_result($resaco,0,'q05_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,63,331,'','".AddSlashes(pg_result($resaco,0,'q05_histor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,63,332,'','".AddSlashes(pg_result($resaco,0,'q05_aliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,63,333,'','".AddSlashes(pg_result($resaco,0,'q05_bruto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,63,334,'','".AddSlashes(pg_result($resaco,0,'q05_vlrinf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($q05_codigo=null) {
      $this->atualizacampos();
     $sql = " update issvar set ";
     $virgula = "";
     if(trim($this->q05_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_codigo"])){
       $sql  .= $virgula." q05_codigo = $this->q05_codigo ";
       $virgula = ",";
       if(trim($this->q05_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "q05_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q05_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_numpre"])){
       $sql  .= $virgula." q05_numpre = $this->q05_numpre ";
       $virgula = ",";
       if(trim($this->q05_numpre) == null ){
         $this->erro_sql = " Campo numpre nao Informado.";
         $this->erro_campo = "q05_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q05_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_numpar"])){
       $sql  .= $virgula." q05_numpar = $this->q05_numpar ";
       $virgula = ",";
       if(trim($this->q05_numpar) == null ){
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "q05_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q05_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_valor"])){
       $sql  .= $virgula." q05_valor = $this->q05_valor ";
       $virgula = ",";
       if(trim($this->q05_valor) == null ){
         $this->erro_sql = " Campo valor nao Informado.";
         $this->erro_campo = "q05_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q05_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_ano"])){
       $sql  .= $virgula." q05_ano = $this->q05_ano ";
       $virgula = ",";
       if(trim($this->q05_ano) == null ){
         $this->erro_sql = " Campo ano nao Informado.";
         $this->erro_campo = "q05_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q05_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_mes"])){
       $sql  .= $virgula." q05_mes = $this->q05_mes ";
       $virgula = ",";
       if(trim($this->q05_mes) == null ){
         $this->erro_sql = " Campo mes nao Informado.";
         $this->erro_campo = "q05_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q05_histor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_histor"])){
       $sql  .= $virgula." q05_histor = '$this->q05_histor' ";
       $virgula = ",";
     }
     if(trim($this->q05_aliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_aliq"])){
       $sql  .= $virgula." q05_aliq = $this->q05_aliq ";
       $virgula = ",";
       if(trim($this->q05_aliq) == null ){
         $this->erro_sql = " Campo aliquota nao Informado.";
         $this->erro_campo = "q05_aliq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q05_bruto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_bruto"])){
       $sql  .= $virgula." q05_bruto = $this->q05_bruto ";
       $virgula = ",";
       if(trim($this->q05_bruto) == null ){
         $this->erro_sql = " Campo valor bruto nao Informado.";
         $this->erro_campo = "q05_bruto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q05_vlrinf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q05_vlrinf"])){
       $sql  .= $virgula." q05_vlrinf = $this->q05_vlrinf ";
       $virgula = ",";
       if(trim($this->q05_vlrinf) == null ){
         $this->erro_sql = " Campo valor contribuinte nao Informado.";
         $this->erro_campo = "q05_vlrinf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q05_codigo!=null){
       $sql .= " q05_codigo = $this->q05_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q05_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4851,'$this->q05_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_codigo"]))
           $resac = db_query("insert into db_acount values($acount,63,4851,'".AddSlashes(pg_result($resaco,$conresaco,'q05_codigo'))."','$this->q05_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_numpre"]))
           $resac = db_query("insert into db_acount values($acount,63,326,'".AddSlashes(pg_result($resaco,$conresaco,'q05_numpre'))."','$this->q05_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_numpar"]))
           $resac = db_query("insert into db_acount values($acount,63,327,'".AddSlashes(pg_result($resaco,$conresaco,'q05_numpar'))."','$this->q05_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_valor"]))
           $resac = db_query("insert into db_acount values($acount,63,328,'".AddSlashes(pg_result($resaco,$conresaco,'q05_valor'))."','$this->q05_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_ano"]))
           $resac = db_query("insert into db_acount values($acount,63,329,'".AddSlashes(pg_result($resaco,$conresaco,'q05_ano'))."','$this->q05_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_mes"]))
           $resac = db_query("insert into db_acount values($acount,63,330,'".AddSlashes(pg_result($resaco,$conresaco,'q05_mes'))."','$this->q05_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_histor"]))
           $resac = db_query("insert into db_acount values($acount,63,331,'".AddSlashes(pg_result($resaco,$conresaco,'q05_histor'))."','$this->q05_histor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_aliq"]))
           $resac = db_query("insert into db_acount values($acount,63,332,'".AddSlashes(pg_result($resaco,$conresaco,'q05_aliq'))."','$this->q05_aliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_bruto"]))
           $resac = db_query("insert into db_acount values($acount,63,333,'".AddSlashes(pg_result($resaco,$conresaco,'q05_bruto'))."','$this->q05_bruto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q05_vlrinf"]))
           $resac = db_query("insert into db_acount values($acount,63,334,'".AddSlashes(pg_result($resaco,$conresaco,'q05_vlrinf'))."','$this->q05_vlrinf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q05_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q05_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q05_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($q05_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q05_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4851,'$q05_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,63,4851,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,63,326,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,63,327,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,63,328,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,63,329,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,63,330,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,63,331,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_histor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,63,332,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_aliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,63,333,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_bruto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,63,334,'','".AddSlashes(pg_result($resaco,$iresaco,'q05_vlrinf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from issvar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q05_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q05_codigo = $q05_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q05_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q05_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q05_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:issvar";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function excluir_issvar($codigo,$codlev=0){
    $sql    = $this->sql_query_file($codigo);
    $result = $this->sql_record($sql);
    $numrows = $this->numrows;
    if($numrows>0){

      $q05_codigo = pg_result($result,0,"q05_codigo");
      $q05_numpre = pg_result($result,0,"q05_numpre");
      $q05_numpar = pg_result($result,0,"q05_numpar");
      $q05_valor  = pg_result($result,0,"q05_valor");
      $q05_ano    = pg_result($result,0,"q05_ano");
      $q05_mes    = pg_result($result,0,"q05_mes");
      $q05_histor = pg_result($result,0,"q05_histor");
      $q05_aliq   = pg_result($result,0,"q05_aliq");
      $q05_bruto  = pg_result($result,0,"q05_bruto");
      $q05_vlrinf = pg_result($result,0,"q05_vlrinf");

      $sql_in = "insert into issvarold(
        q22_codlev,
        q22_codigo,
        q22_numpre,
        q22_numpar,
        q22_valor ,
        q22_ano   ,
        q22_mes   ,
        q22_histor,
        q22_aliq  ,
        q22_bruto ,
        q22_vlrinf
          )
          values (
              '$codlev',
              '$q05_codigo',
              '$q05_numpre',
              '$q05_numpar',
              '$q05_valor' ,
              '$q05_ano'   ,
              '$q05_mes'   ,
              '$q05_histor',
              '$q05_aliq'  ,
              '$q05_bruto' ,
              '".(isset($q05_vlrinf)&&$q05_vlrinf!=0?$q05_vlrinf:0)."'

              )
          ";

      $result = @db_query($sql_in);
      if ($result==false){

        $this->erro_status="0";
        $this->erro_msg="Erro ao incluir em Issvarold";
        //echo "\n erro ao incluir no issvarold";
        // exit;

      }else{
        $sqlnotas = "select * from issvarnotas where q06_codigo = $codigo";

        $rsnotas  = @db_query($sqlnotas);
        $erronota = false;
        if (@pg_num_rows($rsnotas) > 0){

          while ($lnnotas = pg_fetch_array($rsnotas)){

            $ins   = "insert into issvarnotasold (q16_codigo,q16_seq,q16_nota,q16_valor)
              values (".$lnnotas["q06_codigo"].",".$lnnotas["q06_seq"].",'".$lnnotas["q06_nota"]."',".$lnnotas["q06_valor"].")";

            $rsins = db_query($ins);
            if (!$rsins){

              $erronota = true;
              $this->erro_status="0";
              $this->erro_msg="Erro ao incluir em Issvarnotasold";
              //echo "\n Erro ao incluir em Issvarnotasold";
              //exit;
            }

          }

          if ($erronota == false){
            $cl_issvarnotas = new cl_issvarnotas;
            $cl_issvarnotas->excluir($codigo);

          }
        }

        //---exclui do issvar
        $this->q05_codigo = $codigo;
        $this->excluir($codigo);
      }

      //echo "\n funcionou o metodo excluir_issvar \n";
    }else{
      //echo "\n não funcionou o metodo excluir_issvar \n"; exit;
      $this->erro_status="0";
      $this->erro_msg="Nenhum issvar encontrado com o código $codigo.";
    }
  }
   function incluir_issvar_complementar ($vt = array(),$q02_inscr = null,$iNumCgm = null,$sTipo='P'){

     $errocompl = false;
     if($this->q05_histor=="" and $vt!=""){
       reset($vt);
       $ta=sizeof($vt);
       $vir="";
       $this->q05_histor="REFERENTE NOTAS FISCAIS No.:";
       for($i=0; $i<$ta; $i++){
         $chave=key($vt);
         if(substr($chave,0,6)=="linha_"){
           $sqlerro=false;
           $matri= split("#",$vt[$chave]);
           $this->q05_histor.=$vir.$matri[0];
           $vir=",";
         }
         $proximo=next($vt);
       }

     }

     $this->incluir(null);
     if($this->erro_status=="0"){
       $this->erro_msg;
       $errocompl = true;
     }

     $codigo=$this->q05_codigo;


     if($errocompl==false and $vt!=""){
       $clissvarnotas = new cl_issvarnotas;
       reset($vt);
       $ta=sizeof($vt);
       for($i=0; $i<$ta; $i++){
         $chave=key($vt);
         if(substr($chave,0,6)=="linha_"){
           $matri= split("#",$vt[$chave]);
           $sql = "select max(q06_seq) +1 as seq from issvarnotas where issvarnotas.q06_codigo = $codigo ";
           $result55 = db_query($sql);
           $seq = pg_result($result55,0,"seq");
           $q06_seq = $seq == ""?"1":$seq;
           $clissvarnotas->q06_codigo =$codigo;
           $clissvarnotas->q06_seq    =$q06_seq;
           $clissvarnotas->q06_nota   =$matri[0];
           $clissvarnotas->q06_valor  =$matri[1];
           $clissvarnotas->incluir($codigo,$q06_seq);
           if($clissvarnotas->erro_status=="0"){
             $errocompl=true;
             $this->erro_msg = $clissvarnotas->erro_msg;
           }
         }
         $proximo=next($vt);
       }
     }


     if($errocompl==false && $q02_inscr!=''){
       $clarreinscr = new cl_arreinscr ;
       $clarreinscr->k00_numpre = $this->q05_numpre;
       $clarreinscr->k00_inscr  = $q02_inscr;
       $clarreinscr->k00_perc  = 100;
       // exclui se ja existir, senão da duplicate key
       $clarreinscr->excluir($this->q05_numpre,$q02_inscr);
       $clarreinscr->k00_perc  = 100;
       $clarreinscr->incluir($this->q05_numpre,$q02_inscr);
       if($clarreinscr->erro_status=="0"){
         $errocompl = true;
         $this->erro_msg = $clarreinscr->erro_msg;
       }else{
         $resultcgm  = db_query("select q02_numcgm as z01_numcgm from issbase where q02_inscr = $q02_inscr");
         $z01_numcgm = pg_result($resultcgm,0,"z01_numcgm");
       }
     }else{

       $z01_numcgm = $vt['z01_numcgm'];
     }
     if($errocompl==false){

       $clarrecad      = new cl_arrecad ;
  /*
       $resultpar      = db_query("select * from parissqn");
       $q60_tipo       = pg_result($resultpar,0,"q60_tipo");
       $q60_receit     = pg_result($resultpar,0,"q60_receit");
       $q60_codvencvar = pg_result($resultpar,0,"q60_codvencvar");
  */
       // Obtem a configuração de vencimento
       $clconfvencissqnvariavel      = new cl_confvencissqnvariavel();
       $sWhere                       = "q144_ano = {$this->q05_ano}";
       $sSqlConfVencISSQNVariavel    = $clconfvencissqnvariavel->sql_query_file(null, 'q144_codvenc, q144_receita, q144_tipo', null, $sWhere);
       $rsSqlConfVencISSQNVariavel   = $clconfvencissqnvariavel->sql_record($sSqlConfVencISSQNVariavel);
       $iLinhasConfVencISSQNVariavel = $clconfvencissqnvariavel->numrows;

       if (!$rsSqlConfVencISSQNVariavel || $iLinhasConfVencISSQNVariavel == 0) {

         $errocompl      = true;
         $this->erro_msg = " Não existe configurações cadastradas para a competência({$this->q05_ano})!";
         return false;
       }
       $oConfVencISSQNVariavel = db_utils::fieldsMemory($rsSqlConfVencISSQNVariavel, 0);
       $q60_tipo       = $oConfVencISSQNVariavel->q144_tipo;
       $q60_receit     = $oConfVencISSQNVariavel->q144_receita;
       $q60_codvencvar = $oConfVencISSQNVariavel->q144_codvenc;

       $clarrecad->k00_receit = $q60_receit;

       if ( $sTipo == 'P' ) {
         $clarrecad->k00_tipo = $q60_tipo;
       } else {

         $rsConfPlan = db_query("select w10_tipo from db_confplan");
         $w10_tipo   = pg_result($rsConfPlan,0,'w10_tipo');
         $clarrecad->k00_tipo = $w10_tipo;
       }

       $sqlvenc    = "select q82_venc,q82_hist from cadvenc where q82_codigo = $q60_codvencvar and q82_parc = ".$this->q05_mes;
       $resultvenc = db_query($sqlvenc);
       $q82_hist   = pg_result($resultvenc,0,"q82_hist");
       $q82_venc   = pg_result($resultvenc,0,"q82_venc");

       $clarrecad->k00_hist = $q82_hist;

       if($this->q05_ano == db_getsession("DB_anousu") ){
         $clarrecad->k00_dtvenc = "$q82_venc";
       }else{
         $res = db_query("select * from db_confplan");
         if(pg_num_rows($res) > 0){
           $w10_dia = pg_result($res,0,"w10_dia");

         }else{
           $errocompl = true;
           $this->erro_msg = "Tabela db_confplan vazia!";
         }

         $qmes = $this->q05_mes;
         $qano = $this->q05_ano;
         $qmes += 1;
         if($qmes > 12){
           $qmes = 1;
           $qano += 1;
         }
         $qmes         = str_pad($qmes,2,"0",STR_PAD_LEFT);
         $venc_arrecad = $qano."-".$qmes."-".$w10_dia;
         $clarrecad->k00_dtvenc = "$venc_arrecad";
       }

       if ($iNumCgm != null) {
         $iCgm = $iNumCgm;
       }else{
         $iCgm = $z01_numcgm;
       }

       $clarrecad->k00_numcgm = $iCgm;
       $clarrecad->k00_dtoper = $clarrecad->k00_dtvenc;
       $clarrecad->k00_valor  = $this->q05_valor;
       $clarrecad->k00_numpre = $this->q05_numpre;
       $clarrecad->k00_numtot = 1;
       $clarrecad->k00_numpar = $this->q05_mes;
       $clarrecad->k00_numdig ='0';
       $clarrecad->k00_tipojm ='0';
       $clarrecad->incluir();

       if($clarrecad->erro_status=="0"){
         $errocompl = true;
         $this->erro_msg = "Arrecad - ".$clarrecad->erro_msg;
       }

     }

     if($errocompl==false){
       $this->erro_msg = "Inclusão efetuada com sucesso !";
       $this->q05_valor="";
       $this->q05_bruto="";
       unset($this->q05_histor);
       unset($q06_nota);
       unset($q06_valor);
       return true;

     }else{
       $this->erro_status = "0";
       return false;
     }

     if ($sTipo == 'T' && !$errocompl) {

       if (!class_exists('cl_issplan')) {
         $this->erro_status = "0";
         $this->erro_msg    = "Classe issplan não definida!";
         return false;
       }

       $rsCgm    = db_query("select * from cgm where z01_numcgm = {$iCgm}");
       $sNomeCgm = pg_result($rsCgm,0,'z01_nome');
       $sFoneCgm = pg_result($rsCgm,0,'z01_telef');

       $clissplan = new cl_issplan();
       $clissplan->q20_ano        = $this->q05_ano;
       $clissplan->q20_mes        = $this->q05_mes;
       $clissplan->q20_numcgm     = $iCgm;
       $clissplan->q20_nomecontri = $sNomeCgm;
       $clissplan->q20_fonecontri = $sFoneCgm;
       $clissplan->q20_numbco     = '';
       $clissplan->q20_numpre     = $this->q05_numpre;
       $clissplan->q20_situacao   = 1;

       $clissplan->incluir(null);

       if ($clissplan->erro_status == 0) {
         $this->erro_status = "0";
         $this->erro_msg    = "IssPlan - ".$clissplan->erro_msg;
         return false;
       }

       if (!class_exists('cl_issplannumpre')) {
         $this->erro_status = "0";
         $this->erro_msg    = "Classe issplannumpre não definida!";
         return false;
       }

       $clissplannumpre = new cl_issplannumpre();
       $clissplannumpre->q32_dataop   = date('Y-m-d',db_getsession('DB_datausu'));
       $clissplannumpre->q32_horaop   = db_hora();
       $clissplannumpre->q32_numpre   = $this->q05_numpre;
       $clissplannumpre->q32_planilha = $clissplan->q20_planilha;
       $clissplannumpre->q32_status   = 1;
       $clissplannumpre->incluir(null);
       if ($clissplannumpre->erro_status == 0) {
         $this->erro_status = "0";
         $this->erro_msg    = "IssPlanNumpre - ".$clissplannumpre->erro_msg;
         return false;
       }
     }
   }

   function sql_query ( $q05_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from issvar ";
     $sql2 = "";
     if($dbwhere==""){
       if($q05_codigo!=null ){
         $sql2 .= " where issvar.q05_codigo = $q05_codigo ";
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
   function sql_query_arrecad ( $q05_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from issvar ";
     $sql .= "      inner join arreinscr  on  issvar.q05_numpre = arreinscr.k00_numpre ";
     $sql.= "       inner join arrecad   on  arreinscr.k00_numpre = arrecad.k00_numpre and issvar.q05_numpar=arrecad.k00_numpar ";

     $sql2 = "";
     if($dbwhere==""){
       if($q05_codigo!=null ){
         $sql2 .= " where issvar.q05_codigo = $q05_codigo ";
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
   function sql_query_arreinscr ( $q05_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from issvar ";
     $sql .= "      inner join arreinscr  on  issvar.q05_numpre = arreinscr.k00_numpre ";
     $sql.= "       left outer join arrecad   on  arreinscr.k00_numpre = arrecad.k00_numpre and issvar.q05_numpar=arrecad.k00_numpar ";
     $sql.= "       left outer join arrepaga  on  arreinscr.k00_numpre = arrepaga.k00_numpre and issvar.q05_numpar=arrepaga.k00_numpar ";

     $sql2 = "";
     if($dbwhere==""){
       if($q05_codigo!=null ){
         $sql2 .= " where issvar.q05_codigo = $q05_codigo ";
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
   function sql_query_arrenumcgm ( $q05_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from arrenumcgm ";
    $sql .= "      inner  join issvar  on  issvar.q05_numpre = arrenumcgm.k00_numpre ";
    $sql.= "       left outer join arrepaga  on  arrenumcgm.k00_numpre = arrepaga.k00_numpre and issvar.q05_numpar=arrepaga.k00_numpar ";

    $sql2 = "";
    if($dbwhere==""){
      if($q05_codigo!=null ){
        $sql2 .= " where issvar.q05_codigo = $q05_codigo ";
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
   function sql_query_arretivprinc ( $q05_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from issvar ";
    $sql .= "      inner join arreinscr  on  issvar.q05_numpre = arreinscr.k00_numpre ";
    $sql .= "      inner join arrecad    on  arreinscr.k00_numpre = arrecad.k00_numpre and issvar.q05_numpar=arrecad.k00_numpar ";
    $sql .= "      inner join issbase    on  issbase.q02_inscr = arreinscr.k00_inscr    ";
    $sql .= "      inner join cgm        on  issbase.q02_numcgm = cgm.z01_numcgm    ";
    $sql .= "      inner join tabativ    on  issbase.q02_inscr = tabativ.q07_inscr      ";
    $sql .= "      inner join ativid     on  ativid.q03_ativ = tabativ.q07_ativ      ";
    $sql .= "      inner join ativprinc  on  ativprinc.q88_inscr = tabativ.q07_inscr and ativprinc.q88_seq = tabativ.q07_seq     ";
    $sql .= "      inner join clasativ   on  clasativ.q82_ativ = tabativ.q07_ativ      ";
    $sql .= "      inner join classe     on  clasativ.q82_classe = classe.q12_classe      ";
    $sql2 = "";
    if($dbwhere==""){
      if($q05_codigo!=null ){
        $sql2 .= " where issvar.q05_codigo = $q05_codigo ";
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
   function sql_query_file ( $q05_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from issvar ";
    $sql2 = "";
    if($dbwhere==""){
      if($q05_codigo!=null ){
        $sql2 .= " where issvar.q05_codigo = $q05_codigo ";
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
   function sql_query_lev ( $q05_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from issvar ";
    $sql2 = "";
    if($dbwhere==""){
      if($q05_codigo!=null ){
        $sql2 .= " where issvar.q05_codigo = $q05_codigo ";
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
   function sql_query_pesq ( $q05_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from issvar ";
    $sql .= "      left join arreinscr  on  issvar.q05_numpre = arreinscr.k00_numpre ";
    $sql .= "      left join arrecad  on issvar.q05_numpre = arrecad.k00_numpre   ";
    $sql2 = "";
    if($dbwhere==""){
      if($q05_codigo!=null ){
        $sql2 .= " where issvar.q05_codigo = $q05_codigo ";
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

  /**
   * método para retornar os dados por
   * competencia e inscricao
   *
   */
  function getDadosCompetenciaInscricao ($iMes, $iAno, $iInscricao) {

    $sCamposDadosCompetenciaInscricao  = "q05_numpre  ,";
    $sCamposDadosCompetenciaInscricao .= "q05_numpar  ,";
    $sCamposDadosCompetenciaInscricao .= "k00_inscr    ";

    $sWhereDadosCompetenciaInscricao   = "q05_mes = {$iMes} and q05_ano = {$iAno} and k00_inscr = {$iInscricao} ";
    $sSqlIssvar                        = $this->sql_query_arreinscr(null,
                                                                    $sCamposDadosCompetenciaInscricao,
                                                                    null,
                                                                    $sWhereDadosCompetenciaInscricao );
    $rsIssvar                          = $this->sql_record($sSqlIssvar);

    if ($this->numrows == 0) {

      return false;
    }

    $oIssvar = db_utils::fieldsMemory($rsIssvar, 0);

    $oDadosIssvar = new stdClass();

    $oDadosIssvar->q05_numpre = $oIssvar->q05_numpre;
    $oDadosIssvar->q05_numpar = $oIssvar->q05_numpar;
    $oDadosIssvar->inscr      = $oIssvar->k00_inscr;
    return $oDadosIssvar;
  }

  function getDadosCompetenciaCgm ($iMes, $iAno, $iNumCgm) {

    $sCamposDadosCompetenciaInscricao  = "q05_numpre  ,";
    $sCamposDadosCompetenciaInscricao .= "q05_numpar  ,";
    $sCamposDadosCompetenciaInscricao .= "k00_numcgm   ";

    $sWhereDadosCompetenciaInscricao   = "q05_mes = {$iMes} and q05_ano = {$iAno} and k00_numcgm = {$iNumCgm} ";

    $sSqlIssvar  = "select {$sCamposDadosCompetenciaInscricao} ";
    $sSqlIssvar .= "   from issvar ";
    $sSqlIssvar .= "  inner join arrecad on issvar.q05_numpre = k00_numpre where {$sWhereDadosCompetenciaInscricao} ";

    $rsIssvar                          = $this->sql_record($sSqlIssvar);

    if ($this->numrows == 0) {

      return false;
    }
    $oIssvar                           = db_utils::fieldsMemory($rsIssvar, 0);
    $oDadosIssvar = new stdClass();
    $oDadosIssvar->q05_numpre = $oIssvar->q05_numpre;
    $oDadosIssvar->q05_numpar = $oIssvar->q05_numpar;
    $oDadosIssvar->inscr      = $oIssvar->k00_numcgm;

    return $oDadosIssvar;

  }

  function gerarIssqnVariavelComplementar(DBDate $oDataVencimento, $iReceitaDebito = 0, $vt = array(),$q02_inscr = null,$iNumCgm = null,$sTipo='P'){

    $errocompl = false;
    if($this->q05_histor=="" and $vt!=""){
      reset($vt);
      $ta=sizeof($vt);
      $vir="";
      $this->q05_histor="REFERENTE NOTAS FISCAIS No.:";
      for($i=0; $i<$ta; $i++){
        $chave=key($vt);
        if(substr($chave,0,6)=="linha_"){
          $sqlerro=false;
          $matri= split("#",$vt[$chave]);
          $this->q05_histor.=$vir.$matri[0];
          $vir=",";
        }
        $proximo=next($vt);
      }

    }

    $this->incluir(null);
    if($this->erro_status=="0"){
      $this->erro_msg;
      $errocompl = true;
    }

    $codigo=$this->q05_codigo;


    if($errocompl==false and $vt!=""){
      $clissvarnotas = new cl_issvarnotas;
      reset($vt);
      $ta=sizeof($vt);
      for($i=0; $i<$ta; $i++){
        $chave=key($vt);
        if(substr($chave,0,6)=="linha_"){
          $matri= split("#",$vt[$chave]);
          $sql = "select max(q06_seq) +1 as seq from issvarnotas where issvarnotas.q06_codigo = $codigo ";
          $result55 = db_query($sql);
          $seq = pg_result($result55,0,"seq");
          $q06_seq = $seq == ""?"1":$seq;
          $clissvarnotas->q06_codigo =$codigo;
          $clissvarnotas->q06_seq    =$q06_seq;
          $clissvarnotas->q06_nota   =$matri[0];
          $clissvarnotas->q06_valor  =$matri[1];
          $clissvarnotas->incluir($codigo,$q06_seq);
          if($clissvarnotas->erro_status=="0"){
            $errocompl=true;
            $this->erro_msg = $clissvarnotas->erro_msg;
          }
        }
        $proximo=next($vt);
      }
    }


    if($errocompl==false && $q02_inscr!=''){
      $clarreinscr = new cl_arreinscr ;
      $clarreinscr->k00_numpre = $this->q05_numpre;
      $clarreinscr->k00_inscr  = $q02_inscr;
      $clarreinscr->k00_perc  = 100;
      // exclui se ja existir, senão da duplicate key
      $clarreinscr->excluir($this->q05_numpre,$q02_inscr);
      $clarreinscr->k00_perc  = 100;
      $clarreinscr->incluir($this->q05_numpre,$q02_inscr);
      if($clarreinscr->erro_status=="0"){
        $errocompl = true;
        $this->erro_msg = $clarreinscr->erro_msg;
      }else{
        $resultcgm  = db_query("select q02_numcgm as z01_numcgm from issbase where q02_inscr = $q02_inscr");
        $z01_numcgm = pg_result($resultcgm,0,"z01_numcgm");
      }
    }else{

      $z01_numcgm = $vt['z01_numcgm'];
    }
    if($errocompl==false){

      $clarrecad      = new cl_arrecad ;
      $resultpar      = db_query("select * from parissqn");
      $q60_tipo       = pg_result($resultpar,0,"q60_tipo");
      $q60_receit     = pg_result($resultpar,0,"q60_receit");
      $q60_codvencvar = pg_result($resultpar,0,"q60_codvencvar");

      if ( !empty($iReceitaDebito) ) {
        $q60_receit     = $iReceitaDebito;
      }

      $clarrecad->k00_receit = $q60_receit;

      if ( $sTipo == 'P' ) {

        $clarrecad->k00_tipo = $q60_tipo;
      } else {

        $rsConfPlan = db_query("select w10_tipo from db_confplan");
        $w10_tipo   = pg_result($rsConfPlan,0,'w10_tipo');
        $clarrecad->k00_tipo = $w10_tipo;
      }

      $sqlvenc    = "select q82_venc,q82_hist from cadvenc where q82_codigo = $q60_codvencvar and q82_parc = ".$this->q05_mes;
      $resultvenc = db_query($sqlvenc);
      $q82_venc   = pg_result($resultvenc,0,"q82_venc");
      $q82_hist   = pg_result($resultvenc,0,"q82_hist");

      $clarrecad->k00_hist = $q82_hist;
      if($this->q05_ano == db_getsession("DB_anousu")){
        $clarrecad->k00_dtvenc = $oDataVencimento->getDate(DBDate::DATA_EN);//"$q82_venc";

      }else{
        $res = db_query("select * from db_confplan");
        if(pg_num_rows($res) > 0){
          $w10_dia = pg_result($res,0,"w10_dia");

        }else{
          $errocompl = true;
          $this->erro_msg = "Tabela db_confplan vazia!";
        }

        $qmes = $this->q05_mes;
        $qano = $this->q05_ano;
        $qmes += 1;
        if($qmes > 12){
          $qmes = 1;
          $qano += 1;
        }
        $qmes         = str_pad($qmes,2,"0",STR_PAD_LEFT);
        $venc_arrecad = $qano."-".$qmes."-".$w10_dia;
        $clarrecad->k00_dtvenc = $oDataVencimento->getDate(DBDate::DATA_EN);//"$venc_arrecad";
      }

      if ($iNumCgm != null) {
        $iCgm = $iNumCgm;
      }else{
        $iCgm = $z01_numcgm;
      }

      $clarrecad->k00_numcgm = $iCgm;
      $clarrecad->k00_dtoper = $clarrecad->k00_dtvenc;
      $clarrecad->k00_valor  = $this->q05_valor;
      $clarrecad->k00_numpre = $this->q05_numpre;
      $clarrecad->k00_numtot = 1;
      $clarrecad->k00_numpar = $this->q05_mes;
      $clarrecad->k00_numdig ='0';
      $clarrecad->k00_tipojm ='0';
      $clarrecad->incluir();


      if($clarrecad->erro_status=="0"){
        $errocompl = true;
        $this->erro_msg = "Arrecad - ".$clarrecad->erro_msg;
      }

    }

    if($errocompl==false){
      $this->erro_msg = "Inclusão efetuada com sucesso !";
      $this->q05_valor="";
      $this->q05_bruto="";
      unset($this->q05_histor);
      unset($q06_nota);
      unset($q06_valor);

    }else{
      $this->erro_status = "0";
      return false;
    }

    if ($sTipo == 'T' && !$errocompl) {

      if (!class_exists('cl_issplan')) {
        $this->erro_status = "0";
        $this->erro_msg    = "Classe issplan não definida!";
        return false;
      }

      $rsCgm    = db_query("select * from cgm where z01_numcgm = {$iCgm}");
      $sNomeCgm = pg_result($rsCgm,0,'z01_nome');
      $sFoneCgm = pg_result($rsCgm,0,'z01_telef');

      $clissplan = new cl_issplan();
      $clissplan->q20_ano        = $this->q05_ano;
      $clissplan->q20_mes        = $this->q05_mes;
      $clissplan->q20_numcgm     = $iCgm;
      $clissplan->q20_nomecontri = addslashes($sNomeCgm);
      $clissplan->q20_fonecontri = $sFoneCgm;
      $clissplan->q20_numbco     = '';
      $clissplan->q20_numpre     = $this->q05_numpre;
      $clissplan->q20_situacao   = 1;

      $clissplan->incluir(null);

      if ($clissplan->erro_status == 0) {
        $this->erro_status = "0";
        $this->erro_msg    = "IssPlan - ".$clissplan->erro_msg;
        return false;
      }

      if (!class_exists('cl_issplannumpre')) {
        $this->erro_status = "0";
        $this->erro_msg    = "Classe issplannumpre não definida!";
        return false;
      }

      $clissplannumpre = new cl_issplannumpre();
      $clissplannumpre->q32_dataop   = date('Y-m-d',db_getsession('DB_datausu'));
      $clissplannumpre->q32_horaop   = db_hora();
      $clissplannumpre->q32_numpre   = $this->q05_numpre;
      $clissplannumpre->q32_planilha = $clissplan->q20_planilha;
      $clissplannumpre->q32_status   = 1;
      $clissplannumpre->incluir(null);
      if ($clissplannumpre->erro_status == 0) {
        $this->erro_status = "0";
        $this->erro_msg    = "IssPlanNumpre - ".$clissplannumpre->erro_msg;
        return false;
      }
    }
  }

  /**
   * Metodo para retornar a lista de debitos para cancelamento de Issqn
   *
   * @param integer $iMes
   * @param integer $iAno
   * @param integer $iInscricaoMunicipal
   * @param string $sSituacao
   * @return boolean|stdClass
   */
  function getDadosCompetenciaSituacaoInscricao($iMes, $iAno, $iInscricaoMunicipal, $sSituacao) {

    switch ($sSituacao) {
      case 'incluir' :
        $sSql = "
          select issvar.q05_numpre,
                 issvar.q05_numpar,
                 issvar.q05_histor,
                 issvar.q05_valor,
                 arreinscr.k00_inscr,
                 arrecad.k00_valor
          from arreinscr
               inner join issvar  on issvar.q05_numpre  = arreinscr.k00_numpre
               inner join arrecad on arrecad.k00_numpre = q05_numpre
                                 and k00_numpar         = issvar.q05_numpar
          where arreinscr.k00_inscr = {$iInscricaoMunicipal}
            and issvar.q05_valor    = 0
            and issvar.q05_mes      = {$iMes}
            and issvar.q05_ano      = {$iAno}
          order by issvar.q05_ano, issvar.q05_mes";
        break;

      case 'excluir' :
          $sSql = "
            select issvar.q05_numpre,
                   issvar.q05_numpar,
                   issvar.q05_histor,
                   issvar.q05_valor,
                   arreinscr.k00_inscr,
                   arrecant.k00_valor
            from arreinscr
                 inner join issvar   on issvar.q05_numpre   = arreinscr.k00_numpre
                 inner join arrecant on arrecant.k00_numpre = q05_numpre
                                    and k00_numpar          = issvar.q05_numpar
            where arreinscr.k00_inscr = {$iInscricaoMunicipal}
              and arrecant.k00_valor  = 0
              and issvar.q05_valor    = 0
              and issvar.q05_mes      = {$iMes}
              and issvar.q05_ano      = {$iAno}
            order by issvar.q05_ano, issvar.q05_mes";
      break;
    }

    $rsRetorno = $this->sql_record($sSql);

    if ($this->numrows == 0) {
      return false;
    }

    $oRetorno = db_utils::fieldsMemory($rsRetorno, 0);

    $oDadosRetorno = new stdClass();
    $oDadosRetorno->inscricaomunicipal = $oRetorno->k00_inscr;
    $oDadosRetorno->mes                = $oRetorno->q05_mes;
    $oDadosRetorno->ano                = $oRetorno->q05_ano;
    $oDadosRetorno->numpre             = $oRetorno->q05_numpre;
    $oDadosRetorno->numpar             = $oRetorno->q05_numpar;

    return $oDadosRetorno;
  }

  /**
   * Inclui ISSQN Váriavel utilizado pelo nfse
   *
   * @param array $vt
   * @param null|integer $q02_inscr
   * @param null|integer $iNumCgm
   * @return bool
   */
  function incluir_issvar_nfse($vt = array(), $q02_inscr = null, $iNumCgm = null) {

    $errocompl = false;
    if ($this->q05_histor == '' && $vt != '') {

      reset($vt);

      $ta               = sizeof($vt);
      $vir              = '';
      $this->q05_histor = 'REFERENTE NOTAS FISCAIS No.:';

      for ($i = 0; $i < $ta; $i++) {

        $chave = key($vt);
        if (substr($chave, 0, 6) == 'linha_') {

          $sqlerro           = false;
          $matri             = explode('#', $vt[$chave]);
          $this->q05_histor .= $vir . $matri[0];
          $vir = ',';
        }

        $proximo = next($vt);
      }
    }

    // Verifica se existir um registro na issvar para aquele ano, mes e numpre apenas altera o seu valor
    // para não duplicar na consulta geral financeira.
    $sWhereIssVar  = "q05_numpre={$this->q05_numpre} and q05_mes={$this->q05_mes} and q05_ano = {$this->q05_ano}";
    $sSqlIssVar    = $this->sql_query_file(null,'*',null,$sWhereIssVar);
    $rsDadosIssVar = $this->sql_record($sSqlIssVar);

    if (pg_num_rows($rsDadosIssVar) > 0) {

      $oIssVar = db_utils::fieldsMemory($rsDadosIssVar, 0);

      $this->q05_codigo = $oIssVar->q05_codigo;
      //$this->q05_histor = $oIssVar->q05_histor;
      $this->q05_aliq   = $oIssVar->q05_aliq;
      $this->q05_bruto  = $oIssVar->q05_bruto;
      $this->q05_vlrinf = $oIssVar->q05_vlrinf;
      $this->alterar($this->q05_codigo);
    } else {
      $this->incluir(null);
    }

    if ($this->erro_status == '0') {

      $this->erro_msg;
      $errocompl = true;
    }

    $codigo = $this->q05_codigo;
    if ($errocompl == false and $vt != '') {

      $clissvarnotas = new cl_issvarnotas();
      reset($vt);
      $ta = sizeof($vt);

      for ($i = 0; $i < $ta; $i++) {

        $chave = key($vt);
        if (substr($chave, 0, 6) == 'linha_') {

          $matri                     = explode('#', $vt[$chave]);
          $sql                       = "select max(q06_seq) +1 as seq from issvarnotas where issvarnotas.q06_codigo = $codigo ";
          $result55                  = db_query($sql);
          $seq                       = pg_result($result55, 0, 'seq');
          $q06_seq                   = ($seq == '') ? '1' : $seq;

          $clissvarnotas->q06_codigo = $codigo;
          $clissvarnotas->q06_seq    = $q06_seq;
          $clissvarnotas->q06_nota   = $matri[0];
          $clissvarnotas->q06_valor  = $matri[1];
          $clissvarnotas->incluir($codigo, $q06_seq);

          if ($clissvarnotas->erro_status == '0') {

            $errocompl      = true;
            $this->erro_msg = $clissvarnotas->erro_msg;
          }
        }

        $proximo = next($vt);
      }
    }

    if ($errocompl == false && $q02_inscr != '') {

      $clarreinscr             = new cl_arreinscr();
      $clarreinscr->k00_numpre = $this->q05_numpre;
      $clarreinscr->k00_inscr  = $q02_inscr;
      $clarreinscr->k00_perc   = 100;

      // exclui se ja existir, senão da duplicate key
      $clarreinscr->excluir($this->q05_numpre, $q02_inscr);
      $clarreinscr->k00_perc = 100;
      $clarreinscr->incluir($this->q05_numpre, $q02_inscr);

      if ($clarreinscr->erro_status == '0') {

        $errocompl      = true;
        $this->erro_msg = $clarreinscr->erro_msg;
      } else {

        $resultcgm  = db_query("select q02_numcgm as z01_numcgm from issbase where q02_inscr = {$q02_inscr}");
        $z01_numcgm = pg_result($resultcgm, 0, "z01_numcgm");
      }
    } else {
      $z01_numcgm = $vt['z01_numcgm'];
    }

    if ($errocompl == false) {

      $clarrecad = new cl_arrecad();

      // Obtem a configuração de vencimento
      $clconfvencissqnvariavel      = new cl_confvencissqnvariavel();
      $sWhere                       = "q144_ano = {$this->q05_ano}";
      $sSqlConfVencISSQNVariavel    = $clconfvencissqnvariavel->sql_query_file(null, '*', null, $sWhere);
      $rsSqlConfVencISSQNVariavel   = $clconfvencissqnvariavel->sql_record($sSqlConfVencISSQNVariavel);
      $iLinhasConfVencISSQNVariavel = $clconfvencissqnvariavel->numrows;

      if ($iLinhasConfVencISSQNVariavel == 0) {

        $errocompl      = true;
        $this->erro_msg = " Não existe configurações cadastradas para a competência({$this->q05_ano})!";
      } else {

        $oConfVencISSQNVariavel = db_utils::fieldsMemory($rsSqlConfVencISSQNVariavel, 0);

        $dtVencimento          = strtotime("{$this->q05_ano}-{$this->q05_mes}-{$oConfVencISSQNVariavel->q144_diavenc}");
        $dtNovoVencimento      = date('Y-m-d', strtotime('+1 month', $dtVencimento));
        $clarrecad->k00_dtvenc = $dtNovoVencimento;

        if ($iNumCgm != null) {
          $iCgm = $iNumCgm;
        } else {
          $iCgm = $z01_numcgm;
        }

        $clarrecad->k00_numcgm = $iCgm;
        $clarrecad->k00_dtoper = $clarrecad->k00_dtvenc;
        $clarrecad->k00_valor  = $this->q05_valor;
        $clarrecad->k00_numpre = $this->q05_numpre;
        $clarrecad->k00_numpar = $this->q05_mes;
        $clarrecad->k00_numdig = '0';
        $clarrecad->k00_tipojm = '0';

        // Verifica se existe alguma parcela em aberto, se existir atualiza apenas os valores e datas devidos.
        $sWhereArrecad  = "k00_numpre={$this->q05_numpre} and k00_numpar={$this->q05_mes}";
        $sSqlArrecad    = $clarrecad->sql_query_file(null,'*',null,$sWhereArrecad);
        $rsDadosArrecad = $clarrecad->sql_record($sSqlArrecad);

        if (pg_num_rows($rsDadosArrecad) > 0) {

          $oArrecad = db_utils::fieldsMemory($rsDadosArrecad, 0);

          $clarrecad->k00_numtot = $oArrecad->k00_numtot;
          $clarrecad->alterar(null, $sWhereArrecad);
        } else {

          $clarrecad->k00_numtot = 1;
          $clarrecad->k00_receit = $oConfVencISSQNVariavel->q144_receita;
          $clarrecad->k00_tipo   = $oConfVencISSQNVariavel->q144_tipo;
          $clarrecad->k00_hist   = $oConfVencISSQNVariavel->q144_hist;
          $clarrecad->incluir();
        }

        if ($clarrecad->erro_status == '0') {

          $errocompl      = true;
          $this->erro_msg = "Arrecad - {$clarrecad->erro_msg}";
        }
      }
    }

    if ($errocompl == false) {

      $this->erro_msg  = 'Inclusão efetuada com sucesso.';
      $this->q05_valor = '';
      $this->q05_bruto = '';

      unset($this->q05_histor);
      unset($q06_nota);
      unset($q06_valor);

      return true;
    } else {

      $this->erro_status = '0';

      return false;
    }
  }

  /**
   * Inclui ISSQN Retido utilizado pelo nfse
   *
   * @param array $vt
   * @param null  $q02_inscr
   * @param null  $iNumCgm
   * @return bool
   */
  function incluir_issvar_dms($vt = array(), $q02_inscr = null, $iNumCgm = null) {

    $errocompl = false;
    if ($this->q05_histor == '' && $vt != '') {

      reset($vt);
      $ta  = sizeof($vt);
      $vir = '';
      $this->q05_histor = 'REFERENTE NOTAS FISCAIS No.:';

      for ($i=0; $i<$ta; $i++) {

        $chave=key($vt);
        if (substr($chave,0,6) == 'linha_') {

          $sqlerro = false;
          $matri   = explode('#',$vt[$chave]);
          $this->q05_histor .= $vir.$matri[0];
          $vir = ',';
        }

        $proximo = next($vt);
      }
    }

    $this->incluir(null);
    if ($this->erro_status == '0') {

      $this->erro_msg;
      $errocompl = true;
    }

    $codigo = $this->q05_codigo;

    if ($errocompl == false and $vt != '') {

      $clissvarnotas = new cl_issvarnotas();
      reset($vt);
      $ta = sizeof($vt);

      for ($i=0; $i<$ta; $i++) {

        $chave = key($vt);
        if (substr($chave,0,6) == 'linha_') {

          $matri    = explode('#',$vt[$chave]);
          $sql      = "select max(q06_seq) +1 as seq from issvarnotas where issvarnotas.q06_codigo = {$codigo}";
          $result55 = db_query($sql);
          $seq      = pg_result($result55, 0, 'seq');
          $q06_seq  = ($seq == '') ? '1' : $seq;

          $clissvarnotas->q06_codigo = $codigo;
          $clissvarnotas->q06_seq    = $q06_seq;
          $clissvarnotas->q06_nota   = $matri[0];
          $clissvarnotas->q06_valor  = $matri[1];
          $clissvarnotas->incluir($codigo,$q06_seq);
          if ($clissvarnotas->erro_status == '0') {

            $errocompl      = true;
            $this->erro_msg = $clissvarnotas->erro_msg;
          }
        }

        $proximo = next($vt);
      }
    }

    if ($errocompl == false && $q02_inscr != '') {

      $clarreinscr = new cl_arreinscr();
      $clarreinscr->k00_numpre = $this->q05_numpre;
      $clarreinscr->k00_inscr  = $q02_inscr;
      $clarreinscr->k00_perc   = 100;

      // exclui se ja existir, senão da duplicate key
      $clarreinscr->excluir($this->q05_numpre, $q02_inscr);

      $clarreinscr->k00_perc = 100;
      $clarreinscr->incluir($this->q05_numpre,$q02_inscr);
      if ($clarreinscr->erro_status == '0') {

        $errocompl = true;
        $this->erro_msg = $clarreinscr->erro_msg;
      } else {

        $resultcgm  = db_query("select q02_numcgm as z01_numcgm from issbase where q02_inscr = $q02_inscr");
        $z01_numcgm = pg_result($resultcgm,0,"z01_numcgm");
      }
    } else {
      $z01_numcgm = $vt['z01_numcgm'];
    }

    if ($errocompl == false) {

      $rsConfPlan = db_query('select * from db_confplan');
      if (pg_num_rows($rsConfPlan) > 0) {

        $w10_dia    = pg_result($rsConfPlan,0,"w10_dia");
        $w10_tipo   = pg_result($rsConfPlan,0,'w10_tipo');
        $w10_hist   = pg_result($rsConfPlan,0,'w10_hist');
        $w10_receit = pg_result($rsConfPlan,0,'w10_receit');
      } else {

        $errocompl      = true;
        $this->erro_msg = 'Tabela db_confplan vazia!';
      }

      $qmes  = $this->q05_mes;
      $qano  = $this->q05_ano;
      $qmes += 1;

      if ($qmes > 12) {

        $qmes  = 1;
        $qano += 1;
      }

      $qmes         = str_pad($qmes, 2, '0', STR_PAD_LEFT);
      $venc_arrecad = "{$qano}-{$qmes}-{$w10_dia}";

      if ($iNumCgm != null) {
        $iCgm = $iNumCgm;
      } else {
        $iCgm = $z01_numcgm;
      }

      $clarrecad = new cl_arrecad();
      $clarrecad->k00_numcgm = $iCgm;
      $clarrecad->k00_dtvenc = $venc_arrecad;
      $clarrecad->k00_dtoper = $venc_arrecad;
      $clarrecad->k00_valor  = $this->q05_valor;
      $clarrecad->k00_numpre = $this->q05_numpre;
      $clarrecad->k00_numtot = 1;
      $clarrecad->k00_numpar = $this->q05_mes;
      $clarrecad->k00_numdig ='0';
      $clarrecad->k00_tipojm ='0';
      $clarrecad->k00_tipo   = $w10_tipo;
      $clarrecad->k00_hist   = $w10_hist;
      $clarrecad->k00_receit = $w10_receit;
      $clarrecad->incluir();

      if ($clarrecad->erro_status == '0') {

        $errocompl      = true;
        $this->erro_msg = "Arrecad - {$clarrecad->erro_msg}";
      }
    }

    if ($errocompl == false) {

      $this->erro_msg  = 'Inclusão efetuada com sucesso!';
      $this->q05_valor = '';
      $this->q05_bruto = '';

      unset($this->q05_histor);
      unset($q06_nota);
      unset($q06_valor);

      return true;
    } else {

      $this->erro_status = '0';

      return false;
    }
  }

  function sql_update_vlrinf($iNumpre, $iNumpar, $iVlrInf){

    $sSql  = " update issvar                  ";
    $sSql .= "    set q05_vlrinf = {$iVlrInf} ";
    $sSql .= "  where q05_numpre = {$iNumpre} ";
    $sSql .= "    and q05_numpar = {$iNumpar} ";

    return $sSql;
  }

  function sql_update_vlrinf_if_null($q05_codigo, $q05_vlrinf){

    $sSql  = " update issvar                     ";
    $sSql .= "    set q05_vlrinf = {$q05_vlrinf} ";
    $sSql .= "  where q05_codigo = {$q05_codigo} ";
    $sSql .= "    and q05_vlrinf = 0             ";

    return $sSql;
  }

  function sql_issvar_isscalc_inscr_comp($iInscricao, $iAno, $iMes) {

    $sSql  = " select *                                                                ";
    $sSql .= "   from issvar                                                           ";
    $sSql .= "        inner join isscalc on isscalc.q01_numpre = issvar.q05_numpre     ";
    $sSql .= "        inner join arreinscr on arreinscr.k00_numpre = issvar.q05_numpre ";
    $sSql .= "  where q01_cadcal in (2, 3)                                             ";
    $sSql .= "    and q05_ano = {$iAno}                                                ";
    $sSql .= "    and q05_mes = {$iMes}                                                ";
    $sSql .= "    and k00_inscr in ({$iInscricao})                                     ";

    return $sSql;
  }

  function sql_isscalc_numpre_numpar($iNumpre, $iNumpar) {

    $sSql  = " select *                                                                ";
    $sSql .= "   from issvar                                                           ";
    $sSql .= "        inner join isscalc on isscalc.q01_numpre = issvar.q05_numpre     ";
    $sSql .= "        inner join arreinscr on arreinscr.k00_numpre = issvar.q05_numpre ";
    $sSql .= "  where q01_cadcal in (2, 3)                                             ";
    $sSql .= "    and q05_numpre = {$iNumpre}                                          ";
    $sSql .= "    and q05_numpar = {$iNumpar}                                          ";

    return $sSql;
  }
}