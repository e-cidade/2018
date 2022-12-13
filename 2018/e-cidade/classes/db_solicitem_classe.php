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

//MODULO: compras
//CLASSE DA ENTIDADE solicitem
class cl_solicitem {
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
   var $pc11_codigo = 0;
   var $pc11_numero = 0;
   var $pc11_seq = 0;
   var $pc11_quant = 0;
   var $pc11_vlrun = 0;
   var $pc11_prazo = null;
   var $pc11_pgto = null;
   var $pc11_resum = null;
   var $pc11_just = null;
   var $pc11_liberado = 'f';
   var $pc11_servicoquantidade = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 pc11_codigo = int8 = Código do registro
                 pc11_numero = int4 = Solicitacao
                 pc11_seq = int4 = Sequencial
                 pc11_quant = float8 = Qtde solicitada
                 pc11_vlrun = float8 = Vlr unit. aprox
                 pc11_prazo = text = Prazo de entrega
                 pc11_pgto = text = condicoes de pagamento
                 pc11_resum = text = Resumo do Item
                 pc11_just = text = justificativa para compra
                 pc11_liberado = bool = Liberar para contabilidade
                 pc11_servicoquantidade = bool = Serviço Controlado por Quantidade
                 ";
   //funcao construtor da classe
   function cl_solicitem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("solicitem");
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
       $this->pc11_codigo = ($this->pc11_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_codigo"]:$this->pc11_codigo);
       $this->pc11_numero = ($this->pc11_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_numero"]:$this->pc11_numero);
       $this->pc11_seq = ($this->pc11_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_seq"]:$this->pc11_seq);
       $this->pc11_quant = ($this->pc11_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_quant"]:$this->pc11_quant);
       $this->pc11_vlrun = ($this->pc11_vlrun == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_vlrun"]:$this->pc11_vlrun);
       $this->pc11_prazo = ($this->pc11_prazo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_prazo"]:$this->pc11_prazo);
       $this->pc11_pgto = ($this->pc11_pgto == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_pgto"]:$this->pc11_pgto);
       $this->pc11_resum = ($this->pc11_resum == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_resum"]:$this->pc11_resum);
       $this->pc11_just = ($this->pc11_just == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_just"]:$this->pc11_just);
       $this->pc11_liberado = ($this->pc11_liberado == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc11_liberado"]:$this->pc11_liberado);
       $this->pc11_servicoquantidade = ($this->pc11_servicoquantidade == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc11_servicoquantidade"]:$this->pc11_servicoquantidade);
     }else{
       $this->pc11_codigo = ($this->pc11_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc11_codigo"]:$this->pc11_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($pc11_codigo){
      $this->atualizacampos();
     if($this->pc11_numero == null ){
       $this->erro_sql = " Campo Solicitacao nao Informado.";
       $this->erro_campo = "pc11_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc11_seq == null ){
       $this->erro_sql = " Campo Sequencial nao Informado.";
       $this->erro_campo = "pc11_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc11_quant == null ){
       $this->erro_sql = " Campo Qtde solicitada nao Informado.";
       $this->erro_campo = "pc11_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc11_vlrun == null ){
       $this->pc11_vlrun = "0";
     }
     if($this->pc11_liberado == null ){
       $this->erro_sql = " Campo Liberar para contabilidade nao Informado.";
       $this->erro_campo = "pc11_liberado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc11_servicoquantidade == null ){
       $this->pc11_servicoquantidade = "false";
     }
     if($pc11_codigo == "" || $pc11_codigo == null ){
       $result = db_query("select nextval('solicitem_pc11_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: solicitem_pc11_codigo_seq do campo: pc11_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->pc11_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from solicitem_pc11_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc11_codigo)){
         $this->erro_sql = " Campo pc11_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc11_codigo = $pc11_codigo;
       }
     }
     if(($this->pc11_codigo == null) || ($this->pc11_codigo == "") ){
       $this->erro_sql = " Campo pc11_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into solicitem(
                                       pc11_codigo
                                      ,pc11_numero
                                      ,pc11_seq
                                      ,pc11_quant
                                      ,pc11_vlrun
                                      ,pc11_prazo
                                      ,pc11_pgto
                                      ,pc11_resum
                                      ,pc11_just
                                      ,pc11_liberado
                                      ,pc11_servicoquantidade
                       )
                values (
                                $this->pc11_codigo
                               ,$this->pc11_numero
                               ,$this->pc11_seq
                               ,$this->pc11_quant
                               ,$this->pc11_vlrun
                               ,'$this->pc11_prazo'
                               ,'$this->pc11_pgto'
                               ,'$this->pc11_resum'
                               ,'$this->pc11_just'
                               ,'$this->pc11_liberado'
                               ,'$this->pc11_servicoquantidade'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "itens da solicitacao de compras ($this->pc11_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "itens da solicitacao de compras já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "itens da solicitacao de compras ($this->pc11_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc11_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc11_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5558,'$this->pc11_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,870,5558,'','".AddSlashes(pg_result($resaco,0,'pc11_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5548,'','".AddSlashes(pg_result($resaco,0,'pc11_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5549,'','".AddSlashes(pg_result($resaco,0,'pc11_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5551,'','".AddSlashes(pg_result($resaco,0,'pc11_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5552,'','".AddSlashes(pg_result($resaco,0,'pc11_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5553,'','".AddSlashes(pg_result($resaco,0,'pc11_prazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5554,'','".AddSlashes(pg_result($resaco,0,'pc11_pgto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5555,'','".AddSlashes(pg_result($resaco,0,'pc11_resum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5556,'','".AddSlashes(pg_result($resaco,0,'pc11_just'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,5557,'','".AddSlashes(pg_result($resaco,0,'pc11_liberado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,870,19696,'','".AddSlashes(pg_result($resaco,0,'pc11_servicoquantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($pc11_codigo=null) {
      $this->atualizacampos();
     $sql = " update solicitem set ";
     $virgula = "";
     if(trim($this->pc11_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_codigo"])){
       $sql  .= $virgula." pc11_codigo = $this->pc11_codigo ";
       $virgula = ",";
       if(trim($this->pc11_codigo) == null ){
         $this->erro_sql = " Campo Código do registro nao Informado.";
         $this->erro_campo = "pc11_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc11_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_numero"])){
       $sql  .= $virgula." pc11_numero = $this->pc11_numero ";
       $virgula = ",";
       if(trim($this->pc11_numero) == null ){
         $this->erro_sql = " Campo Solicitacao nao Informado.";
         $this->erro_campo = "pc11_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc11_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_seq"])){
       $sql  .= $virgula." pc11_seq = $this->pc11_seq ";
       $virgula = ",";
       if(trim($this->pc11_seq) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "pc11_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc11_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_quant"])){
       $sql  .= $virgula." pc11_quant = $this->pc11_quant ";
       $virgula = ",";
       if(trim($this->pc11_quant) == null ){
         $this->erro_sql = " Campo Qtde solicitada nao Informado.";
         $this->erro_campo = "pc11_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc11_vlrun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_vlrun"])){
        if(trim($this->pc11_vlrun)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc11_vlrun"])){
           $this->pc11_vlrun = "0" ;
        }
       $sql  .= $virgula." pc11_vlrun = $this->pc11_vlrun ";
       $virgula = ",";
     }
     if(trim($this->pc11_prazo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_prazo"])){
       $sql  .= $virgula." pc11_prazo = '$this->pc11_prazo' ";
       $virgula = ",";
     }
     if(trim($this->pc11_pgto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_pgto"])){
       $sql  .= $virgula." pc11_pgto = '$this->pc11_pgto' ";
       $virgula = ",";
     }
     if(trim($this->pc11_resum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_resum"])){
       $sql  .= $virgula." pc11_resum = '$this->pc11_resum' ";
       $virgula = ",";
     }
     if(trim($this->pc11_just)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_just"])){
       $sql  .= $virgula." pc11_just = '$this->pc11_just' ";
       $virgula = ",";
     }
     if(trim($this->pc11_liberado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_liberado"])){
       $sql  .= $virgula." pc11_liberado = '$this->pc11_liberado' ";
       $virgula = ",";
       if(trim($this->pc11_liberado) == null ){
         $this->erro_sql = " Campo Liberar para contabilidade nao Informado.";
         $this->erro_campo = "pc11_liberado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc11_servicoquantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc11_servicoquantidade"])){
       $sql  .= $virgula." pc11_servicoquantidade = '$this->pc11_servicoquantidade' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc11_codigo!=null){
       $sql .= " pc11_codigo = $this->pc11_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc11_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5558,'$this->pc11_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_codigo"]) || $this->pc11_codigo != "")
           $resac = db_query("insert into db_acount values($acount,870,5558,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_codigo'))."','$this->pc11_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_numero"]) || $this->pc11_numero != "")
           $resac = db_query("insert into db_acount values($acount,870,5548,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_numero'))."','$this->pc11_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_seq"]) || $this->pc11_seq != "")
           $resac = db_query("insert into db_acount values($acount,870,5549,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_seq'))."','$this->pc11_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_quant"]) || $this->pc11_quant != "")
           $resac = db_query("insert into db_acount values($acount,870,5551,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_quant'))."','$this->pc11_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_vlrun"]) || $this->pc11_vlrun != "")
           $resac = db_query("insert into db_acount values($acount,870,5552,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_vlrun'))."','$this->pc11_vlrun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_prazo"]) || $this->pc11_prazo != "")
           $resac = db_query("insert into db_acount values($acount,870,5553,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_prazo'))."','$this->pc11_prazo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_pgto"]) || $this->pc11_pgto != "")
           $resac = db_query("insert into db_acount values($acount,870,5554,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_pgto'))."','$this->pc11_pgto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_resum"]) || $this->pc11_resum != "")
           $resac = db_query("insert into db_acount values($acount,870,5555,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_resum'))."','$this->pc11_resum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_just"]) || $this->pc11_just != "")
           $resac = db_query("insert into db_acount values($acount,870,5556,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_just'))."','$this->pc11_just',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_liberado"]) || $this->pc11_liberado != "")
           $resac = db_query("insert into db_acount values($acount,870,5557,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_liberado'))."','$this->pc11_liberado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc11_servicoquantidade"]) || $this->pc11_servicoquantidade != "")
           $resac = db_query("insert into db_acount values($acount,870,19696,'".AddSlashes(pg_result($resaco,$conresaco,'pc11_servicoquantidade'))."','$this->pc11_servicoquantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "itens da solicitacao de compras nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc11_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "itens da solicitacao de compras nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc11_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc11_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($pc11_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc11_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5558,'$pc11_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,870,5558,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5548,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5549,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5551,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5552,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5553,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_prazo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5554,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_pgto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5555,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_resum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5556,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_just'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,5557,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_liberado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,870,19696,'','".AddSlashes(pg_result($resaco,$iresaco,'pc11_servicoquantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from solicitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc11_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc11_codigo = $pc11_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "itens da solicitacao de compras nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc11_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "itens da solicitacao de compras nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc11_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc11_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:solicitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitem ";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
   function sql_query_file ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
   function sql_query_pcmater_dotacao( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitem ";
     $sql .= "      inner join solicita         on solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_depart        on db_depart.coddepto   = solicita.pc10_depto";
     $sql .= "      left join db_usuarios       on solicita.pc10_login  = db_usuarios.id_usuario";
     $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater          on pcmater.pc01_codmater     = solicitempcmater.pc16_codmater";
     $sql .= "      left  join pcprocitem       on pcprocitem.pc81_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join solicitemunid    on solicitemunid.pc17_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join matunid          on matunid.m61_codmatunid = solicitemunid.pc17_unid";
     $sql .= "      left  join pcsubgrupo       on pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      left  join pctipo           on pctipo.pc05_codtipo         = pcsubgrupo.pc04_codtipo";
     $sql .= "      left  join pcdotac          on pcdotac.pc13_codigo = solicitem.pc11_codigo ";
     $sql .= "      left  join orcdotacao       on pcdotac.pc13_coddot = orcdotacao.o58_coddot ";
     $sql .= "                                 and pcdotac.pc13_anousu   = orcdotacao.o58_anousu";
     $sql .= "      left  join orcelemento      on orcdotacao.o58_codele = orcelemento.o56_codele ";
     $sql .= "                                 and orcdotacao.o58_anousu = orcelemento.o56_anousu";
     $sql .= "      left  join pcdotaccontrapartida  on pcdotac.pc13_sequencial = pc19_pcdotac ";
     $sql2 = " where 1=1 ";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " and solicitem.pc11_codigo = $pc11_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 .= " and $dbwhere";
     }
     $sql2 .= " and pc10_instit = " . db_getsession("DB_instit");
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
   function sql_query_pcmater ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitem ";
     $sql .= "      inner join solicita         on solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      inner join db_depart        on db_depart.coddepto   = solicita.pc10_depto";
     $sql .= "      left join db_usuarios       on solicita.pc10_login  = db_usuarios.id_usuario";
     $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater          on pcmater.pc01_codmater     = solicitempcmater.pc16_codmater";
     $sql .= "      left  join pcprocitem       on pcprocitem.pc81_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join solicitemunid    on solicitemunid.pc17_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join matunid          on matunid.m61_codmatunid = solicitemunid.pc17_unid";
     $sql .= "      left  join pcsubgrupo       on pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      left  join pctipo           on pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql .= "      left  join pcdotac          on pcdotac.pc13_codigo = solicitem.pc11_codigo ";
     $sql .= "      left  join pcdotaccontrapartida  on pcdotac.pc13_sequencial = pc19_pcdotac ";
     $sql2 = " where 1=1 ";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " and solicitem.pc11_codigo = $pc11_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 .= " and $dbwhere";
     }
     $sql2 .= " and pc10_instit = " . db_getsession("DB_instit");
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
   function sql_query_serv ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitem ";
     $sql .= "      inner  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      inner  join pcmater  on pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      inner  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
   function sql_query_rel ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from solicitem ";
     $sql .= "      left  join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql .= "      left  join db_depart on  db_depart.coddepto = solicita.pc10_depto";
     $sql .= "      left  join pcsugforn on  pcsugforn.pc40_solic = solicita.pc10_numero";
     $sql .= "      left  join cgm on cgm.z01_numcgm =  pcsugforn.pc40_numcgm";
     $sql .= "      left  join pcdotac  on  pcdotac.pc13_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join pcdotaccontrapartida  on  pcdotac.pc13_sequencial = pc19_pcdotac";
     $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
     $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
     $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join pcmater  on pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
     $sql .= "      left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
     $sql .= "      left  join solicitemele  on  solicitemele.pc18_solicitem = solicitem.pc11_codigo";
     $sql .= "      left  join orcelemento  on  orcelemento.o56_codele = solicitemele.pc18_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      left  join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($pc11_codigo!=null ){
         $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
  function sql_query_relmod2 ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitem ";
    $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
    $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
    $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join pcmater  on pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
    $sql .= "      left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
    $sql .= "      left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
    $sql .= "      left  join solicitemele  on  solicitemele.pc18_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join orcelemento a on  a.o56_codele = solicitemele.pc18_codele and a.o56_anousu=".db_getsession("DB_anousu");
    $sql .= "      left  join pcdotac   on  pcdotac.pc13_codigo = solicitem.pc11_codigo";
    $sql .= "      left  join orcreservasol  on  orcreservasol.o82_pcdotac = pcdotac.pc13_sequencial";
    $sql .= "      left  join orcreserva on orcreserva.o80_coddot = pcdotac.pc13_coddot and orcreserva.o80_codres = orcreservasol.o82_codres";
    $sql .= "      left  join orcdotacao   on  orcdotacao.o58_coddot = pcdotac.pc13_coddot
                                           and  orcdotacao.o58_anousu = pcdotac.pc13_anousu";
    $sql .= "      left  join orcprojativ  on  orcprojativ.o55_projativ = orcdotacao.o58_projativ
                                           and  orcprojativ.o55_anousu = orcdotacao.o58_anousu";
    $sql .= "      left  join orcelemento b on b.o56_codele = orcdotacao.o58_codele and b.o56_anousu = ".db_getsession("DB_anousu");
    $sql .= "      left  join orctiporec   on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
    $sql .= "      left  join solicitemregistropreco   on  pc57_solicitem = pc11_codigo";
    $sql .= "      left  join orcunidade   on  orcdotacao.o58_orgao = orcunidade.o41_orgao
                                           and  orcdotacao.o58_anousu = orcunidade.o41_anousu
										   and  orcdotacao.o58_unidade = orcunidade.o41_unidade";
    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
  function sql_query_prot ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitem ";
    $sql .= "      inner join solicita on  solicita.pc10_numero = solicitem.pc11_numero";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
    $sql .= "      inner join solicitemprot on solicitemprot.pc49_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
    $sql .= "      left  join pcprocitem  on  pcprocitem.pc81_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
    $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
    $sql .= "      left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
    $sql .= "      left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
    $sql .= "      left join protprocesso on protprocesso.p58_codproc = solicitemprot.pc49_protprocesso";
    $sql .= "      left join proctransferproc on proctransferproc.p63_codproc = protprocesso.p58_codproc";
    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
  function sql_query_protandam ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitem ";
    $sql .= "      inner join solicita on  solicita.pc10_numero = solicitem.pc11_numero";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
    $sql .= "      inner join solicitemprot on solicitemprot.pc49_solicitem = solicitem.pc11_codigo";
    $sql .= "      inner join protprocesso on protprocesso.p58_codproc = solicitemprot.pc49_protprocesso";
    $sql .= "      inner join procandam on protprocesso.p58_codandam = procandam.p61_codandam";
    $sql .= "      inner join proctransferproc on proctransferproc.p63_codproc = protprocesso.p58_codproc";
    $sql .= "      inner join proctransfer on proctransfer.p62_codtran = proctransferproc.p63_codtran";
    $sql .= "      inner join proctransand on proctransand.p64_codtran = proctransfer.p62_codtran";
    $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
    $sql .= "      left  join pcprocitem  on  pcprocitem.pc81_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
    $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
    $sql .= "      left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
    $sql .= "      left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
    $sql .= "      left  join solandamand  on pc42_codandam  = p61_codandam";
    $sql .= "      left  join solandam  on pc42_solandam  = pc43_codigo";
    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
  function sql_query_solunid ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitem ";
    $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
    $sql .= "      inner join db_config  on  db_config.codigo = solicita.pc10_instit";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
    $sql .= "      inner join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
    $sql .= "      inner join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
    $sql .= "      inner join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo";
    $sql .= "      inner join empempitem on empempitem.e62_sequen = pcprocitem.pc81_codprocitem";
    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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

  function sql_query_valsuplemorcam( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitem ";
    $sql .= "      inner join solicita             on solicita.pc10_numero                = solicitem.pc11_numero";
    $sql .= "      inner join pcdotac              on pcdotac.pc13_codigo                 = solicitem.pc11_codigo";
    $sql .= "      inner join orcdotacao           on orcdotacao.o58_coddot               = pcdotac.pc13_coddot";
    $sql .= "                                     and orcdotacao.o58_anousu               = pcdotac.pc13_anousu";
    $sql .= "      inner join orcelemento          on orcelemento.o56_codele              = orcdotacao.o58_codele";
    $sql .= "                                     and orcelemento.o56_anousu              = orcdotacao.o58_anousu";
    $sql .= "      left  join orcreservasol        on orcreservasol.o82_pcdotac           = pc13_sequencial";
    $sql .= "      left  join orcreserva           on orcreserva.o80_codres               = orcreservasol.o82_codres";
    $sql .= "                                     and orcreserva.o80_coddot               = pcdotac.pc13_coddot";
    $sql .= "                                     and orcreserva.o80_anousu               = pcdotac.pc13_anousu";
    $sql .= "      left  join solicitempcmater     on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo";
    $sql .= "      left  join pcmater              on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater";
    $sql .= "      left  join pcprocitem           on pcprocitem.pc81_solicitem           = solicitem.pc11_codigo";
    $sql .= "      left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
    $sql .= "      left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
    $sql .= "                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
    $sql .= "      left  join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori";
    $sql .= "      left  join pcorcamitemsol       on pc29_solicitem                      = solicitem.pc11_codigo ";
    $sql .= "      left  join pcorcamitemproc      on pc31_pcprocitem                     = pcprocitem.pc81_codprocitem ";
    $sql .= "      left  join pcorcamitem a        on a.pc22_orcamitem                    = pc29_orcamitem ";
    $sql .= "      left  join pcorcamitem b        on b.pc22_orcamitem                    = pc31_orcamitem ";
    $sql .= "      left  join pcorcamforne c       on c.pc21_codorc                       = a.pc22_codorc ";
    $sql .= "      left  join pcorcamforne d       on d.pc21_codorc                       = b.pc22_codorc ";
    $sql .= "      left  join pcorcamjulg e        on e.pc24_orcamitem                    = a.pc22_orcamitem";
    $sql .= "                                     and e.pc24_orcamforne                   = c.pc21_orcamforne";
    $sql .= "      left  join pcorcamjulg f        on f.pc24_orcamitem                    = b.pc22_orcamitem";
    $sql .= "                                     and f.pc24_orcamforne                   = d.pc21_orcamforne";
    $sql .= "      left  join pcorcamval  g        on g.pc23_orcamitem                    = a.pc22_orcamitem";
    $sql .= "                                     and g.pc23_orcamforne                   = c.pc21_orcamforne";
    $sql .= "      left  join pcorcamval  h        on h.pc23_orcamitem                    = b.pc22_orcamitem
    and h.pc23_orcamforne                   = d.pc21_orcamforne";
    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
  function sql_query_valsuplem ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitem ";
    $sql .= "      inner join solicita    on  solicita.pc10_numero = solicitem.pc11_numero";
    $sql .= "      inner join pcdotac     on  pcdotac.pc13_codigo = solicitem.pc11_codigo";
    $sql .= "      inner join orcdotacao  on  orcdotacao.o58_coddot = pcdotac.pc13_coddot
                                          and  orcdotacao.o58_anousu = pcdotac.pc13_anousu";
    $sql .= "      inner join orcelemento on  orcelemento.o56_codele= orcdotacao.o58_codele and orcelemento.o56_anousu = orcdotacao.o58_anousu";
    $sql .= "      left  join orcreservasol on  orcreservasol.o82_pcdotac = solicitem.pc13_sequencial";
    $sql .= "      left  join orcreserva  on  orcreserva.o80_codres = orcreservasol.o82_codres
                                          and  orcreserva.o80_coddot = pcdotac.pc13_coddot
                                          and  orcreserva.o80_anousu = pcdotac.pc13_anousu";
    $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join pcmater     on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
    $sql .= "      left  join pcprocitem  on  pcprocitem.pc81_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join empautitem  on  empautitem.e55_sequen = pcprocitem.pc81_codprocitem";
    $sql .= "      left  join empautoriza on  empautoriza.e54_autori= empautitem.e55_autori";
    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
  function sql_query_mat ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitem ";
    $sql .= "      inner  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
    $sql .= "      inner  join pcmater  on pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
  function sql_query_dot ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere="",$having=""){
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
    $sql .= " from solicitem ";
    $sql .= "      inner join solicita  on solicita.pc10_numero = solicitem.pc11_numero";
    $sql .= "      left  join pcdotac   on pcdotac.pc13_codigo = solicitem.pc11_codigo ";
    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
      }
      }else if($dbwhere != ""){
          $sql2 = " where $dbwhere";
          }
          $sql .= $sql2;
          if(trim($having) != ""){
            $sql .= $having;
          }
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
  function sql_query_ancoradotorc ( $pc13_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitem ";
    $sql .= "      left  join pcdotac   on pc13_codigo = pc11_codigo ";
    $sql2 = "";
    if($dbwhere==""){
      if($pc13_codigo!=null ){
        $sql2 .= "  where pc11_codigo = $pc13_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = "  where $dbwhere";
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
  function sql_query_vinculo ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitem ";
    $sql .= "      inner join solicita         on solicita.pc10_numero = solicitem.pc11_numero";
    $sql .= "      inner join db_depart        on db_depart.coddepto   = solicita.pc10_depto";
    $sql .= "      left  join db_usuarios      on solicita.pc10_login  = db_usuarios.id_usuario";
    $sql .= "      left  join solicitemvinculo on pc55_solicitemfilho  = solicitem.pc11_codigo";
    $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join pcmater          on pcmater.pc01_codmater     = solicitempcmater.pc16_codmater";
    $sql .= "      left  join pcprocitem       on pcprocitem.pc81_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join solicitemunid    on solicitemunid.pc17_codigo = solicitem.pc11_codigo";
    $sql .= "      left  join matunid          on matunid.m61_codmatunid = solicitemunid.pc17_unid";
    $sql .= "      left  join pcsubgrupo       on pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
    $sql .= "      left  join pctipo           on pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
    $sql .= "      left  join pcdotac          on pcdotac.pc13_codigo = solicitem.pc11_codigo ";
    $sql .= "      left  join pcdotaccontrapartida  on pcdotac.pc13_sequencial = pc19_pcdotac ";
    $sql2 = " where 1=1 ";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " and solicitem.pc11_codigo = $pc11_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 .= " and $dbwhere";
    }
//     $sql2 .= " and pc10_instit = " . db_getsession("DB_instit");
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
   * Retorna uma string sql com os dados  da os items de uma estimativa que esteja em outro departamento
   * @return string sql
   */
  public function sql_query_item_outras_estimativas ($pc11_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

    $sSql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){

        $sSql   .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {

      $sSql .= $campos;
    }
    $sSql .= "  from solicitemvinculo vincdoa";
    $sSql .= "        inner join solicitemvinculo vincrecebe  on vincrecebe.pc55_solicitemfilho  = vincdoa.pc55_solicitemfilho";
    $sSql .= "        inner join solicitem estimativarecebe   on vincrecebe.pc55_solicitempai    = estimativarecebe.pc11_codigo";
    $sSql .= "        inner join solicita                     on estimativarecebe.pc11_numero    = pc10_numero";
    $sSqlWhere = '';
    if ($dbwhere == "") {
      if ($pc11_codigo != null ) {
        $sSqlWhere  .= "  where vincdoa.pc53_solicitempai = {$pc11_codigo} ";
      }
    } else if ($dbwhere != "") {
      $sSqlWhere .= " where $dbwhere";
    }
    $sSql .= $sSqlWhere;
    if ($ordem != null ){

      $sSql       .= " order by ";
      $campos_sql  = split("#", $ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql    .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sSql;
  }


  /* Retorna uma string sql com os dados  da compilação, suas estimativas e Registros de precos realizados
   * @return string sql
  */
  public function sql_query_compilacao_estimativa_rp ($pc11_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

    $sSql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){

        $sSql   .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {

      $sSql .= $campos;
    }

    $sSql .= "  from solicitem registropreco";
    $sSql .= "        inner join solicita on registropreco.pc11_numero                    = pc10_numero ";
    $sSql .= "        inner join solicitemvinculo vinccomp on registropreco.pc11_codigo   = vinccomp.pc55_solicitemfilho";
    $sSql .= "        inner join solicitem comp            on comp.pc11_codigo            = vinccomp.pc55_solicitempai";
    $sSql .= "        inner join solicitemvinculo vincest  on comp.pc11_codigo            = vincest.pc55_solicitemfilho";
    $sSql .= "        inner join solicitem itemestimativa  on vincest.pc55_solicitempai   = itemestimativa.pc11_codigo";
    $sSql .= "        left join solicitaanulada            on pc67_solicita               = pc10_numero";

    $sSqlWhere = '';
    if ($dbwhere == "") {
      if ($pc11_codigo != null ) {
        $sSqlWhere  .= " where comp.pc11_codigo = {$pc11_codigo} ";
      }
    } else if ($dbwhere != "") {
      $sSqlWhere .= " where $dbwhere";
    }

    $sSql .= $sSqlWhere;
    if ($ordem != null ){

      $sSql       .= " order by ";
      $campos_sql  = split("#", $ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql    .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sSql;
  }


  public function sql_query_compilacao_estimativa_empenhado ($pc11_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

    $sSql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){

        $sSql   .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {

      $sSql .= $campos;
    }
    $sSql .= "  from solicitem registropreco";
    $sSql .= "        inner join solicita on registropreco.pc11_numero                    = pc10_numero ";
    $sSql .= "        inner join solicitemvinculo vinccomp on registropreco.pc11_codigo   = vinccomp.pc55_solicitemfilho";
    $sSql .= "        inner join solicitem comp            on comp.pc11_codigo            = vinccomp.pc55_solicitempai";
    $sSql .= "        inner join solicitemvinculo vincest  on comp.pc11_codigo            = vincest.pc55_solicitemfilho";
    $sSql .= "        inner join solicitem itemestimativa  on vincest.pc55_solicitempai   = itemestimativa.pc11_codigo";
    $sSql .= "        inner join pcprocitem                on registropreco.pc11_codigo      = pc81_solicitem ";
    $sSql .= "        inner join empautitempcprocitem      on pc81_codprocitem               = e73_pcprocitem";
    $sSql .= "        inner join empautitem                on e73_sequen                     = e55_sequen";
    $sSql .= "                                            and e73_autori                     = e55_autori";
    $sSql .= "        inner join empautoriza               on e55_autori                     = e54_autori";
    $sSql .= "        inner join empempaut                 on e61_autori                     = e54_autori";
    $sSql .= "        inner join empempenho                on e61_numemp                     = e60_numemp";
    $sSql .= "        inner join empempitem                on e60_numemp                     = e62_numemp";
    $sSql .= "                                            and e62_sequen                     = e55_sequen";
    $sSqlWhere = '';
    if ($dbwhere == "") {
      if ($pc11_codigo != null ) {
        $sSqlWhere  .= " where comp.pc11_codigo = {$pc11_codigo} ";
      }
    } else if ($dbwhere != "") {
      $sSqlWhere .= " where $dbwhere";
    }
    $sSql .= $sSqlWhere;
    if ($ordem != null ){

      $sSql       .= " order by ";
      $campos_sql  = split("#", $ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql    .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sSql;
  }


  public function sql_query_solicitaprotprocesso ($pc11_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
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
    $sql .= " from solicitem ";
    $sql .= "      inner join solicita             on solicita.pc10_numero = solicitem.pc11_numero ";
    $sql .= "      left  join solicitaprotprocesso on solicitaprotprocesso.pc90_solicita = solicita.pc10_numero ";
    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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


  public function sql_query_item_processo_compras ($pc11_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

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
    $sql .= " from solicitem ";
    $sql .= "      inner join solicita         on solicita.pc10_numero            = solicitem.pc11_numero ";
    $sql .= "      left  join solicitaprotprocesso on solicitaprotprocesso.pc90_solicita = solicita.pc10_numero";
    $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join pcmater          on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater";
    $sql .= "      left  join pcprocitem       on pcprocitem.pc81_solicitem       = solicitem.pc11_codigo";
    $sql .= "      left  join solicitemunid    on solicitemunid.pc17_codigo       = solicitem.pc11_codigo";
    $sql .= "      left  join matunid          on matunid.m61_codmatunid          = solicitemunid.pc17_unid";
    $sql .= "      left  join solicitemele     on solicitemele.pc18_solicitem     = solicitem.pc11_codigo ";
    $sql .= "      left  join orcelemento      on solicitemele.pc18_codele        = orcelemento.o56_codele";
    $sql .= "                                 and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
    $sql2 = " where 1=1 ";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " and solicitem.pc11_codigo = $pc11_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 .= " and $dbwhere";
    }
    $sql2 .= " and pc10_instit = " . db_getsession("DB_instit");
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


  function sql_query_solicitacao_orcamento ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitem ";
    $sql .= "      left  join solicita       on  solicita.pc10_numero           = solicitem.pc11_numero";
    $sql .= "      left  join db_depart      on  db_depart.coddepto             = solicita.pc10_depto";
    $sql .= "      left  join pcsugforn      on  pcsugforn.pc40_solic           = solicita.pc10_numero";
    $sql .= "      left  join cgm            on cgm.z01_numcgm                  =  pcsugforn.pc40_numcgm";
    $sql .= "      left  join pcdotac        on  pcdotac.pc13_codigo            = solicitem.pc11_codigo";
    $sql .= "      left  join pcdotaccontrapartida  on  pcdotac.pc13_sequencial = pc19_pcdotac";
    $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo     = solicitem.pc11_codigo";
    $sql .= "      left  join matunid        on  matunid.m61_codmatunid        = solicitemunid.pc17_unid";
    $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join pcmater        on pcmater.pc01_codmater          = solicitempcmater.pc16_codmater";
    $sql .= "      left  join pcsubgrupo     on  pcsubgrupo.pc04_codsubgrupo   = pcmater.pc01_codsubgrupo";
    $sql .= "      left  join pctipo         on  pctipo.pc05_codtipo           = pcsubgrupo.pc04_codtipo";
    $sql .= "      left  join solicitemele   on  solicitemele.pc18_solicitem   = solicitem.pc11_codigo";
    $sql .= "      left  join orcelemento    on  orcelemento.o56_codele        = solicitemele.pc18_codele ";
    $sql .= "                               and orcelemento.o56_anousu         = ".db_getsession("DB_anousu");
    $sql .= "      left  join pcprocitem     on pcprocitem.pc81_solicitem      = solicitem.pc11_codigo";
    $sql .= " 		 left  join pcorcamitemsol on pcorcamitemsol.pc29_solicitem  = solicitem.pc11_codigo           ";
    $sql .= " 		 left  join pcorcamitem    on pcorcamitem.pc22_orcamitem     = pcorcamitemsol.pc29_orcamitem   ";
    $sql .= " 		 left  join pcorcam        on pcorcam.pc20_codorc            = pcorcamitem.pc22_codorc         ";
    $sql .= " 		 left  join pcorcamforne   on pcorcamforne.pc21_codorc       = pcorcam.pc20_codorc             ";
    $sql .= " 		 left  join pcorcamjulg    on pcorcamjulg.pc24_orcamforne    = pcorcamforne.pc21_orcamforne    ";
    $sql .= " 		 			 										and pcorcamjulg.pc24_orcamitem     = pcorcamitem.pc22_orcamitem      ";
    $sql .= " 		 			 										and pcorcamjulg.pc24_pontuacao     = 1                               ";
    $sql .= " 		 left  join pcorcamval     on pcorcamval.pc23_orcamforne     = pcorcamforne.pc21_orcamforne    ";
    $sql .= " 		 													and pcorcamval.pc23_orcamitem      = pcorcamitem.pc22_orcamitem      ";

    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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


  function sql_query_itens_solicitacao ( $pc91_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitapendencia ";
    $sql .= "      inner join solicita  on solicita.pc10_numero  = solicitapendencia.pc91_solicita ";
    $sql .= "      inner join solicitem on solicitem.pc11_numero = solicita.pc10_numero           ";
    $sql2 = "";
    if($dbwhere==""){
      if($pc91_sequencial!=null ){
        $sql2 .= " where solicitapendencia.pc91_sequencial = $pc91_sequencial ";
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


  function sql_query_relmod3 ( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitem ";
    $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
    $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
    $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join pcmater  on pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
    $sql .= "      left  join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
    $sql .= "      left  join pctipo  on  pctipo.pc05_codtipo = pcsubgrupo.pc04_codtipo";
    $sql .= "      left  join solicitemele  on  solicitemele.pc18_solicitem = solicitem.pc11_codigo";
    $sql .= "      left  join orcelemento a on  a.o56_codele = solicitemele.pc18_codele and a.o56_anousu=".db_getsession("DB_anousu");
    $sql .= "      left  join pcdotac   on  pcdotac.pc13_codigo = solicitem.pc11_codigo";
    $sql .= "      left  join orcreservasol  on  orcreservasol.o82_pcdotac = pcdotac.pc13_sequencial";
    $sql .= "      left  join orcreserva on orcreserva.o80_coddot = pcdotac.pc13_coddot and orcreserva.o80_codres = orcreservasol.o82_codres";
    $sql .= "      left  join orcdotacao   on  orcdotacao.o58_coddot = pcdotac.pc13_coddot
        and  orcdotacao.o58_anousu = pcdotac.pc13_anousu";
    $sql .= "      left  join orcprojativ  on  orcprojativ.o55_projativ = orcdotacao.o58_projativ
        and  orcprojativ.o55_anousu = orcdotacao.o58_anousu";
    $sql .= "      left  join orcelemento b on b.o56_codele = orcdotacao.o58_codele and b.o56_anousu = ".db_getsession("DB_anousu");
    $sql .= "      left  join orctiporec   on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
    $sql .= "      left  join solicitemregistropreco   on  pc57_solicitem = pc11_codigo";
    $sql .= "      left  join orcunidade   on  orcdotacao.o58_orgao = orcunidade.o41_orgao
        and  orcdotacao.o58_anousu = orcunidade.o41_anousu
        and  orcdotacao.o58_unidade = orcunidade.o41_unidade";

    $sql .= " 		 left join pcorcamitemsol on pcorcamitemsol.pc29_solicitem  = solicitem.pc11_codigo           ";
    $sql .= " 		 left join pcorcamitem    on pcorcamitem.pc22_orcamitem     = pcorcamitemsol.pc29_orcamitem   ";
    $sql .= " 		 left join pcorcam        on pcorcam.pc20_codorc            = pcorcamitem.pc22_codorc         ";
    $sql .= " 		 left join pcorcamforne   on pcorcamforne.pc21_codorc       = pcorcam.pc20_codorc             ";
    $sql .= " 		 left join pcorcamjulg    on pcorcamjulg.pc24_orcamforne    = pcorcamforne.pc21_orcamforne    ";
    $sql .= " 		 													and pcorcamjulg.pc24_orcamitem    = pcorcamitem.pc22_orcamitem      ";
    $sql .= " 		 													and pcorcamjulg.pc24_pontuacao    = 1                               ";
    $sql .= " 		 left join pcorcamval     on pcorcamval.pc23_orcamforne     = pcorcamforne.pc21_orcamforne    ";
    $sql .= " 		 													and pcorcamval.pc23_orcamitem     = pcorcamitem.pc22_orcamitem      ";

    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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



  function sql_query_desdobramento( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from solicitem ";
    $sql .= "inner join solicitemele on solicitem.pc11_codigo = solicitemele.pc18_solicitem ";
    $sql .= "inner join orcelemento  on solicitemele.pc18_codele = orcelemento.o56_codele
                                    and orcelemento.o56_anousu =". db_getsession("DB_anousu");
    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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

  /*
   * metodo que retorna se um determinado item ja possui autorização
   * utilizado para alterar dotações
   */
  function sql_query_verificaItemAutorizado( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= "      from solicitem                                                                                      ";
    $sql .= "inner join pcprocitem            on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem           ";
    $sql .= "inner join empautitempcprocitem  on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem ";
    $sql .= "inner join empautitem            on empautitempcprocitem.e73_autori = empautitem.e55_autori               ";
    $sql .= "                                and empautitempcprocitem.e73_sequen = empautitem.e55_sequen               ";
    $sql .= "inner join empautoriza           on empautitem.e55_autori           = empautoriza.e54_autori              ";
    $sql .= "inner join empautidot            on empautoriza.e54_autori          = empautidot.e56_autori               ";


    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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
   * query para retornar itens do julgamento
   */

  function sql_query_JulgamentoOrcamento( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= "      from solicitem                                                                                ";
    $sql .= "         inner join pcorcamitemsol on solicitem.pc11_codigo        = pcorcamitemsol.pc29_solicitem  ";
    $sql .= "         inner join pcorcamitem    on pcorcamitem.pc22_orcamitem   = pcorcamitemsol.pc29_orcamitem  ";
    $sql .= "         inner join pcorcam        on pcorcam.pc20_codorc          = pcorcamitem.pc22_codorc        ";
    $sql .= "         inner join pcorcamval     on pcorcamval.pc23_orcamitem    = pcorcamitem.pc22_orcamitem     ";
    $sql .= "         inner join pcorcamforne   on pcorcamforne.pc21_codorc     = pcorcam.pc20_codorc            ";
    $sql .= "                                  and pcorcamforne.pc21_orcamforne = pcorcamval.pc23_orcamforne     ";
    $sql .= "          inner join pcorcamjulg    on pcorcamjulg.pc24_orcamforne  = pcorcamforne.pc21_orcamforne  ";
    $sql .= "                                   and pcorcamjulg.pc24_orcamitem   = pcorcamitem.pc22_orcamitem    ";
    $sql2 = "                                  and pcorcamjulg.pc24_pontuacao   = 1                              ";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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

  public function sql_query_empenhado_liquidado($pc11_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

    $sSql = "select ";
    if ($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){

        $sSql   .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {

      $sSql .= $campos;
    }
    $sSql .= "  from solicitem ";
    $sSql .= "    inner join pcprocitem            on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem ";
    $sSql .= "    inner join empautitempcprocitem  on pcprocitem.pc81_codprocitem     = empautitempcprocitem.e73_pcprocitem";
    $sSql .= "    inner join empautitem            on empautitempcprocitem.e73_sequen = empautitem.e55_sequen";
    $sSql .= "                                    and empautitempcprocitem.e73_autori = empautitem.e55_autori";
    $sSql .= "    inner join empautoriza           on empautitem.e55_autori           = empautoriza.e54_autori";
    $sSql .= "    inner join empempaut             on empempaut.e61_autori            = empautoriza.e54_autori";
    $sSql .= "    inner join empempenho            on empempaut.e61_numemp            = empempenho.e60_numemp";
    $sSql .= "    inner join empempitem            on empempenho.e60_numemp           = empempitem.e62_numemp";
    $sSql .= "                                    and empempitem.e62_sequen           = empautitem.e55_sequen";
    $sSql .= "    inner join empnotaitem           on empnotaitem.e72_empempitem      = empempitem.e62_sequencial";

    $sSqlWhere = '';
    if ($dbwhere == "") {
      if ($pc11_codigo != null ) {
        $sSqlWhere  .= " where comp.pc11_codigo = {$pc11_codigo} ";
      }
    } else if ($dbwhere != "") {
      $sSqlWhere .= " where $dbwhere";
    }
    $sSql .= $sSqlWhere;
    if ($ordem != null ){

      $sSql       .= " order by ";
      $campos_sql  = split("#", $ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sSql    .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sSql;
  }
  /*
    * metodo que retorna se um determinado item ja possui autorização
    * utilizado para alterar dotações
    */
  function sql_query_item_licitacao( $pc11_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= "      from solicitem                                                                               ";
    $sql .= "            inner join pcprocitem on solicitem.pc11_codigo       = pcprocitem.pc81_solicitem ";
    $sql .= "            inner join liclicitem on pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem ";
    $sql .= "            inner join liclicita  on liclicitem.l21_codliclicita = liclicita.l20_codigo ";

    $sql2 = "";
    if($dbwhere==""){
      if($pc11_codigo!=null ){
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
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

  function sqlItensComValorLancado ( $pc11_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if($campos != "*" ) {

      $campos_sql = split("#",$campos);
      $virgula    = "";

      for($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from solicitem ";
    $sql .= "      left join pcprocitem      on pc81_solicitem    = pc11_codigo ";
    $sql .= "      left join liclicitem      on l21_codpcprocitem = pc81_codprocitem ";
    $sql .= "      left join pcorcamitemlic  on pc26_liclicitem   = l21_codigo ";
    $sql .= "      left join pcorcamval      on pc23_orcamitem    = pc26_orcamitem ";
    $sql .= "      left join pcorcamjulg     on pc24_orcamitem    = pc23_orcamitem ";
    $sql .= "                                and pc24_orcamforne   = pc23_orcamforne ";
    $sql2 = "";

    if($dbwhere == "") {

      if($pc11_codigo != null) {
        $sql2 .= " where solicitem.pc11_codigo = $pc11_codigo ";
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }

    $sql .= $sql2;

    if($ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";

      for($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }

    return $sql;
  }
}