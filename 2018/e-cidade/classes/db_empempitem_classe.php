<?
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

//MODULO: empenho
//CLASSE DA ENTIDADE empempitem
class cl_empempitem {
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
   var $e62_sequencial = 0;
   var $e62_numemp = 0;
   var $e62_item = 0;
   var $e62_sequen = 0;
   var $e62_quant = 0;
   var $e62_vltot = 0;
   var $e62_descr = null;
   var $e62_codele = 0;
   var $e62_vlrun = 0;
   var $e62_servicoquantidade = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 e62_sequencial = int4 = Código Sequencial
                 e62_numemp = int4 = Número
                 e62_item = int4 = Código do Material
                 e62_sequen = int4 = Sequencia
                 e62_quant = float8 = Quantidade
                 e62_vltot = float8 = Valor total
                 e62_descr = text = Descrição
                 e62_codele = int4 = Código Elemento
                 e62_vlrun = float8 = Valor Unitário
                 e62_servicoquantidade = bool = Serviço Controlado por Quantidade
                 ";
   //funcao construtor da classe
   function cl_empempitem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empempitem");
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
       $this->e62_sequencial = ($this->e62_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e62_sequencial"]:$this->e62_sequencial);
       $this->e62_numemp = ($this->e62_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e62_numemp"]:$this->e62_numemp);
       $this->e62_item = ($this->e62_item == ""?@$GLOBALS["HTTP_POST_VARS"]["e62_item"]:$this->e62_item);
       $this->e62_sequen = ($this->e62_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["e62_sequen"]:$this->e62_sequen);
       $this->e62_quant = ($this->e62_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["e62_quant"]:$this->e62_quant);
       $this->e62_vltot = ($this->e62_vltot == ""?@$GLOBALS["HTTP_POST_VARS"]["e62_vltot"]:$this->e62_vltot);
       $this->e62_descr = ($this->e62_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["e62_descr"]:$this->e62_descr);
       $this->e62_codele = ($this->e62_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["e62_codele"]:$this->e62_codele);
       $this->e62_vlrun = ($this->e62_vlrun == ""?@$GLOBALS["HTTP_POST_VARS"]["e62_vlrun"]:$this->e62_vlrun);
       $this->e62_servicoquantidade = ($this->e62_servicoquantidade == "f"?@$GLOBALS["HTTP_POST_VARS"]["e62_servicoquantidade"]:$this->e62_servicoquantidade);
     }else{
       $this->e62_sequencial = ($this->e62_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e62_sequencial"]:$this->e62_sequencial);
       $this->e62_sequen = ($this->e62_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["e62_sequen"]:$this->e62_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($e62_numemp,$e62_sequen){
      $this->atualizacampos();
     if($this->e62_numemp == null ){
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "e62_numemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e62_item == null ){
       $this->erro_sql = " Campo Código do Material nao Informado.";
       $this->erro_campo = "e62_item";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e62_quant == null ){
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "e62_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e62_vltot == null ){
       $this->erro_sql = " Campo Valor total nao Informado.";
       $this->erro_campo = "e62_vltot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e62_codele == null ){
       $this->erro_sql = " Campo Código Elemento nao Informado.";
       $this->erro_campo = "e62_codele";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e62_vlrun == null ){
       $this->erro_sql = " Campo Valor Unitário nao Informado.";
       $this->erro_campo = "e62_vlrun";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if ($this->e62_servicoquantidade == null) {
       $this->e62_servicoquantidade = "false";
     }

     //if($e62_sequencial == "" || $e62_sequencial == null ){
       $result = db_query("select nextval('empempitem_e62_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empempitem_e62_sequencial_seq do campo: e62_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->e62_sequencial = pg_result($result,0,0);
     /*
     }else{
       $result = db_query("select last_value from empempitem_e62_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e62_sequencial)){
         $this->erro_sql = " Campo e62_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e62_sequencial = $e62_sequencial;
       }
     }
     */
     if(($this->e62_sequencial == null) || ($this->e62_sequencial == "") ){
       $this->erro_sql = " Campo e62_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empempitem(
                                       e62_sequencial
                                      ,e62_numemp
                                      ,e62_item
                                      ,e62_sequen
                                      ,e62_quant
                                      ,e62_vltot
                                      ,e62_descr
                                      ,e62_codele
                                      ,e62_vlrun
                                      ,e62_servicoquantidade
                       )
                values (
                                $this->e62_sequencial
                               ,$this->e62_numemp
                               ,$this->e62_item
                               ,$this->e62_sequen
                               ,$this->e62_quant
                               ,$this->e62_vltot
                               ,'$this->e62_descr'
                               ,$this->e62_codele
                               ,$this->e62_vlrun
                               ,'$this->e62_servicoquantidade'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ítens dos empenhos ($this->e62_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ítens dos empenhos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ítens dos empenhos ($this->e62_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e62_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e62_numemp,$this->e62_sequen));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10848,'$this->e62_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,891,10848,'','".AddSlashes(pg_result($resaco,0,'e62_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,891,5666,'','".AddSlashes(pg_result($resaco,0,'e62_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,891,5667,'','".AddSlashes(pg_result($resaco,0,'e62_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,891,5668,'','".AddSlashes(pg_result($resaco,0,'e62_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,891,5669,'','".AddSlashes(pg_result($resaco,0,'e62_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,891,5670,'','".AddSlashes(pg_result($resaco,0,'e62_vltot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,891,5671,'','".AddSlashes(pg_result($resaco,0,'e62_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,891,5717,'','".AddSlashes(pg_result($resaco,0,'e62_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,891,7427,'','".AddSlashes(pg_result($resaco,0,'e62_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,891,19699,'','".AddSlashes(pg_result($resaco,0,'e62_servicoquantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($e62_numemp=null,$e62_sequen=null) {
      $this->atualizacampos();
     $sql = " update empempitem set ";
     $virgula = "";
     if(trim($this->e62_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e62_sequencial"])){
       $sql  .= $virgula." e62_sequencial = $this->e62_sequencial ";
       $virgula = ",";
       if(trim($this->e62_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e62_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e62_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e62_numemp"])){
       $sql  .= $virgula." e62_numemp = $this->e62_numemp ";
       $virgula = ",";
       if(trim($this->e62_numemp) == null ){
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "e62_numemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e62_item)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e62_item"])){
       $sql  .= $virgula." e62_item = $this->e62_item ";
       $virgula = ",";
       if(trim($this->e62_item) == null ){
         $this->erro_sql = " Campo Código do Material nao Informado.";
         $this->erro_campo = "e62_item";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e62_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e62_sequen"])){
       $sql  .= $virgula." e62_sequen = $this->e62_sequen ";
       $virgula = ",";
       if(trim($this->e62_sequen) == null ){
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "e62_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e62_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e62_quant"])){
       $sql  .= $virgula." e62_quant = $this->e62_quant ";
       $virgula = ",";
       if(trim($this->e62_quant) == null ){
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "e62_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e62_vltot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e62_vltot"])){
       $sql  .= $virgula." e62_vltot = $this->e62_vltot ";
       $virgula = ",";
       if(trim($this->e62_vltot) == null ){
         $this->erro_sql = " Campo Valor total nao Informado.";
         $this->erro_campo = "e62_vltot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e62_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e62_descr"])){
       $sql  .= $virgula." e62_descr = '$this->e62_descr' ";
       $virgula = ",";
     }
     if(trim($this->e62_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e62_codele"])){
       $sql  .= $virgula." e62_codele = $this->e62_codele ";
       $virgula = ",";
       if(trim($this->e62_codele) == null ){
         $this->erro_sql = " Campo Código Elemento nao Informado.";
         $this->erro_campo = "e62_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e62_vlrun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e62_vlrun"])){
       $sql  .= $virgula." e62_vlrun = $this->e62_vlrun ";
       $virgula = ",";
       if(trim($this->e62_vlrun) == null ){
         $this->erro_sql = " Campo Valor Unitário nao Informado.";
         $this->erro_campo = "e62_vlrun";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e62_servicoquantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e62_servicoquantidade"])){
       $sql  .= $virgula." e62_servicoquantidade = '$this->e62_servicoquantidade' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($e62_numemp!=null){
       $sql .= " e62_numemp = $this->e62_numemp";
     }
        if($e62_sequen!=null){
       $sql .= " and  e62_sequen = $this->e62_sequen";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e62_numemp,$this->e62_sequen));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10848,'$this->e62_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e62_sequencial"]) || $this->e62_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,891,10848,'".AddSlashes(pg_result($resaco,$conresaco,'e62_sequencial'))."','$this->e62_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e62_numemp"]) || $this->e62_numemp != "")
           $resac = db_query("insert into db_acount values($acount,891,5666,'".AddSlashes(pg_result($resaco,$conresaco,'e62_numemp'))."','$this->e62_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e62_item"]) || $this->e62_item != "")
           $resac = db_query("insert into db_acount values($acount,891,5667,'".AddSlashes(pg_result($resaco,$conresaco,'e62_item'))."','$this->e62_item',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e62_sequen"]) || $this->e62_sequen != "")
           $resac = db_query("insert into db_acount values($acount,891,5668,'".AddSlashes(pg_result($resaco,$conresaco,'e62_sequen'))."','$this->e62_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e62_quant"]) || $this->e62_quant != "")
           $resac = db_query("insert into db_acount values($acount,891,5669,'".AddSlashes(pg_result($resaco,$conresaco,'e62_quant'))."','$this->e62_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e62_vltot"]) || $this->e62_vltot != "")
           $resac = db_query("insert into db_acount values($acount,891,5670,'".AddSlashes(pg_result($resaco,$conresaco,'e62_vltot'))."','$this->e62_vltot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e62_descr"]) || $this->e62_descr != "")
           $resac = db_query("insert into db_acount values($acount,891,5671,'".AddSlashes(pg_result($resaco,$conresaco,'e62_descr'))."','$this->e62_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e62_codele"]) || $this->e62_codele != "")
           $resac = db_query("insert into db_acount values($acount,891,5717,'".AddSlashes(pg_result($resaco,$conresaco,'e62_codele'))."','$this->e62_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e62_vlrun"]) || $this->e62_vlrun != "")
           $resac = db_query("insert into db_acount values($acount,891,7427,'".AddSlashes(pg_result($resaco,$conresaco,'e62_vlrun'))."','$this->e62_vlrun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e62_servicoquantidade"]) || $this->e62_servicoquantidade != "")
           $resac = db_query("insert into db_acount values($acount,891,19699,'".AddSlashes(pg_result($resaco,$conresaco,'e62_servicoquantidade'))."','$this->e62_servicoquantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ítens dos empenhos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e62_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ítens dos empenhos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($e62_numemp=null,$e62_sequen=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e62_numemp,$e62_sequen));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10848,'$this->e62_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,891,10848,'','".AddSlashes(pg_result($resaco,$iresaco,'e62_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,891,5666,'','".AddSlashes(pg_result($resaco,$iresaco,'e62_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,891,5667,'','".AddSlashes(pg_result($resaco,$iresaco,'e62_item'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,891,5668,'','".AddSlashes(pg_result($resaco,$iresaco,'e62_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,891,5669,'','".AddSlashes(pg_result($resaco,$iresaco,'e62_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,891,5670,'','".AddSlashes(pg_result($resaco,$iresaco,'e62_vltot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,891,5671,'','".AddSlashes(pg_result($resaco,$iresaco,'e62_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,891,5717,'','".AddSlashes(pg_result($resaco,$iresaco,'e62_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,891,7427,'','".AddSlashes(pg_result($resaco,$iresaco,'e62_vlrun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,891,19699,'','".AddSlashes(pg_result($resaco,$iresaco,'e62_servicoquantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empempitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e62_numemp != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e62_numemp = $e62_numemp ";
        }
        if($e62_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e62_sequen = $e62_sequen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ítens dos empenhos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e62_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ítens dos empenhos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e62_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e62_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empempitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $e62_numemp=null,$e62_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empempitem ";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = empempitem.e62_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = empempitem.e62_item";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empempitem.e62_numemp";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcmater.pc01_id_usuario";
     $sql .= "      inner join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
     $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql .= "      left  join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
     $sql2 = "";
     if($dbwhere==""){
       if($e62_numemp!=null ){
         $sql2 .= " where empempitem.e62_numemp = $e62_numemp ";
       }
       if($e62_sequen!=null ){
       if($sql2!=""){
       $sql2 .= " and ";
       }else{
         $sql2 .= " where ";
       }
         $sql2 .= " empempitem.e62_sequen = $e62_sequen ";
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
         function sql_query_elemento ( $e55_autori=null,$e55_sequen=null,$ordem=null,$dbwhere=""){
         $sql = "select ";

         $campos = "e55_codele,sum(e55_vltot) as e55_vltot";
         $sql .= $campos;
         $sql .= " from empautitem ";
         $sql2 = "";
         if($dbwhere==""){
         if($e55_autori!=null ){
         $sql2 .= " where empautitem.e55_autori = $e55_autori ";
         }
         if($e55_sequen!=null ){
         if($sql2!=""){
         $sql2 .= " and ";
         }else{
         $sql2 .= " where ";
         }
           $sql2 .= " empautitem.e55_sequen = $e55_sequen ";
           }
           }else if($dbwhere != ""){
             $sql2 = " where $dbwhere";
         }
         $sql .= $sql2;
         $sql .="group by e55_codele";
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
           function sql_query_file ( $e62_numemp=null,$e62_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
             $sql .= " from empempitem ";
             $sql2 = "";
             if($dbwhere==""){
             if($e62_numemp!=null ){
               $sql2 .= " where empempitem.e62_numemp = $e62_numemp ";
               }
               if($e62_sequen!=null ){
               if($sql2!=""){
                 $sql2 .= " and ";
                 }else{
                 $sql2 .= " where ";
                 }
                 $sql2 .= " empempitem.e62_sequen = $e62_sequen ";
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
   function sql_query_imprimerel ( $e62_numemp=null,$e62_sequen=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from empempitem ";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = empempitem.e62_codele and orcelemento.o56_anousu = ".db_getsession("DB_anousu");
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = empempitem.e62_item";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empempitem.e62_numemp";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
     $sql2 = "";
     if($dbwhere==""){
       if($e62_numemp!=null ){
         $sql2 .= " where empempitem.e62_numemp = $e62_numemp ";
       }
       if($e62_sequen!=null ){
       if($sql2!=""){
       $sql2 .= " and ";
       }else{
         $sql2 .= " where ";
       }
         $sql2 .= " empempitem.e62_sequen = $e62_sequen ";
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

         function sql_query_item_pacto ( $e62_numemp=null,$e62_sequen=null,$campos="*",$ordem=null,$dbwhere="") {

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
         $sql .= " from empempitem ";
         $sql .= "      inner join empempenho              on  e62_numemp          = e60_numemp";
         $sql .= "      inner join pactovalormovempempitem on  o105_empempitem     = e62_sequencial";
         $sql .= "      inner join pactovalormov           on  o105_pactovalormov  = o88_sequencial";
         $sql .= "      left  join empnotaitem             on  e72_empempitem      = e62_sequencial";
         $sql2 = "";
         if($dbwhere==""){
         if($e62_numemp !=null ){
         $sql2 .= " where empempitem.e62_numemp = $e62_numemp ";
         }
           if($e62_sequen!=null ){
               if($sql2!=""){
               $sql2 .= " and ";
   }else{
               $sql2 .= " where ";
               }
               $sql2 .= " empempitem.e62_sequen = $e62_sequen ";
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

   function sql_query_left_item_pacto ( $e62_numemp=null,$e62_sequen=null,$campos="*",$ordem=null,$dbwhere="") {

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
   $sql .= " from empempitem ";
   $sql .= "      inner join empempenho              on  e62_numemp          = e60_numemp";
   $sql .= "      left join pactovalormovempempitem on  o105_empempitem     = e62_sequencial";
     $sql .= "      left join pactovalormov           on  o105_pactovalormov  = o88_sequencial";
     $sql .= "      left  join empnotaitem             on  e72_empempitem      = e62_sequencial";
     $sql2 = "";
     if($dbwhere==""){
     if($e62_numemp !=null ){
         $sql2 .= " where empempitem.e62_numemp = $e62_numemp ";
     }
     if($e62_sequen!=null ){
     if($sql2!=""){
     $sql2 .= " and ";
   }else{
   $sql2 .= " where ";
   }
   $sql2 .= " empempitem.e62_sequen = $e62_sequen ";
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
   * Busca material do almoxarifado pelo item do empenho
   *
   * @param string $sCampos
   * @param string $sOrdem
   * @param string $sWhere
   * @access public
   * @return string
   */
  public function sql_query_material_almoxarifado($sCampos = '*', $sOrdem = null, $sWhere = null, $sGroup = null) {

    $sSql  = "select $sCampos                                                              ";
    $sSql .= "   from empempitem                                                           ";
    $sSql .= "        inner join matordemitem       on m52_numemp  = e62_numemp            ";
    $sSql .= "                                     and m52_sequen  = e62_sequen            ";
    $sSql .= "        inner join matestoqueitemoc   on m52_codlanc = m73_codmatordemitem   ";
    $sSql .= "        inner join matestoqueitem     on m71_codlanc = m73_codmatestoqueitem ";
    $sSql .= "        inner join matestoque         on m70_codigo  = m71_codmatestoque     ";

    if (!empty($sWhere)) {
      $sSql .= " where $sWhere ";
    }

    if (!empty($sGroup)) {
      $sSql .= " group by $sGroup ";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by $sOrdem ";
    }

    return $sSql;
  }

  public function sql_query_material_lancamento($sCampos = '*', $sOrdem = null, $sWhere = null, $sGroup = null) {

    $sSql  = "select $sCampos                                                              ";
    $sSql .= "   from empempitem                                                           ";
    $sSql .= "        inner join matordemitem       on m52_numemp  = e62_numemp            ";
    $sSql .= "                                     and m52_sequen  = e62_sequen            ";
    $sSql .= "        inner join matestoqueitemoc   on m52_codlanc = m73_codmatordemitem   ";
    $sSql .= "        inner join matestoqueitem     on m71_codlanc = m73_codmatestoqueitem ";
    $sSql .= "        inner  join matestoqueitemnota on m74_codmatestoqueitem = m71_codlanc ";
    $sSql .= "        inner join matestoque         on m70_codigo  = m71_codmatestoque     ";

    if (!empty($sWhere)) {
      $sSql .= " where $sWhere ";
    }

    if (!empty($sGroup)) {
      $sSql .= " group by $sGroup ";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by $sOrdem ";
    }

    return $sSql;
  }


  public function sql_query_itens_nota_empenho($iSequencialEmpenho) {

    $sqlitem  = " select distinct ";
    $sqlitem .= "	       case when e62_descr = '' then pc01_descrmater else pc01_descrmater||' \\n('||e62_descr||')' end as ";
    $sqlitem .= "        pc01_descrmater, ";
    $sqlitem .= "        e62_sequen, ";
    $sqlitem .= "        e62_numemp, ";
    $sqlitem .= "        e62_quant, ";
    $sqlitem .= "        e62_vltot, ";
    $sqlitem .= "        e62_vlrun, ";
    $sqlitem .= "        e62_codele, ";
    $sqlitem .= "        o56_elemento, ";
    $sqlitem .= "        o56_descr, ";
    $sqlitem .= "        rp.pc81_codproc, ";
    $sqlitem .= "        solrp.pc11_numero, ";
    $sqlitem .= "        solrp.pc11_codigo, ";
    $sqlitem .= "        case when pc10_solicitacaotipo = 5 then coalesce(trim(pcitemvalrp.pc23_obs), '') ";
    $sqlitem .= "             else  coalesce(trim(pcorcamval.pc23_obs), '') end as pc23_obs ";
    $sqlitem .= "   from empempitem ";
    $sqlitem .= "       inner join empempenho           on empempenho.e60_numemp           = empempitem.e62_numemp ";
    $sqlitem .= "       inner join pcmater              on pcmater.pc01_codmater           = empempitem.e62_item ";
    $sqlitem .= "       inner join orcelemento          on orcelemento.o56_codele          = empempitem.e62_codele ";
    $sqlitem .= "                                      and orcelemento.o56_anousu          = empempenho.e60_anousu ";
    $sqlitem .= "       left join empempaut             on empempaut.e61_numemp            = empempenho.e60_numemp ";
    $sqlitem .= "       left join empautitem            on empautitem.e55_autori           = empempaut.e61_autori ";
    $sqlitem .= "                                      and e62_sequen = e55_sequen ";

    // verificação de empenhos de registro de preco

    $sqlitem .= "       left join empautitempcprocitem        on empautitempcprocitem.e73_autori      = empautitem.e55_autori ";
    $sqlitem .= "                                            and empautitempcprocitem.e73_sequen      = empautitem.e55_sequen ";
    $sqlitem .= "       left join pcprocitem rp               on rp.pc81_codprocitem                  = empautitempcprocitem.e73_pcprocitem ";
    $sqlitem .= "       left join solicitem solrp             on solrp.pc11_codigo                    = rp.pc81_solicitem ";
    $sqlitem .= "       left join solicita                    on solicita.pc10_numero                 = solrp.pc11_numero ";
    $sqlitem .= "       left join solicitemvinculo            on solicitemvinculo.pc55_solicitemfilho = solrp.pc11_codigo ";
    $sqlitem .= "       left join solicitem compilacao        on solicitemvinculo.pc55_solicitempai   = compilacao.pc11_codigo ";
    $sqlitem .= "       left join pcprocitem proccompilacao   on pc55_solicitempai                    = proccompilacao.pc81_solicitem ";
    $sqlitem .= "       left join liclicitem licitarp         on proccompilacao.pc81_codprocitem      = licitarp.l21_codpcprocitem ";
    $sqlitem .= "       left join pcorcamitemlic pcitemrp     on licitarp.l21_codigo                  = pcitemrp.pc26_liclicitem ";
    $sqlitem .= "       left join pcorcamjulg julgrp          on pcitemrp.pc26_orcamitem              = julgrp.pc24_orcamitem ";
    $sqlitem .= "                                            and julgrp.pc24_pontuacao                = 1 ";
    $sqlitem .= "       left join pcorcamval pcitemvalrp      on julgrp.pc24_orcamitem                = pcitemvalrp.pc23_orcamitem ";
    $sqlitem .= "                                            and julgrp.pc24_orcamforne               = pcitemvalrp.pc23_orcamforne ";

    //verficaao de empenhos gerados a partir de licitacao normal.

    $sqlitem .= "       left join empautitempcprocitem  pcprocitemaut  on pcprocitemaut.e73_autori        = empautitem.e55_autori ";
    $sqlitem .= "                                                     and pcprocitemaut.e73_sequen        = empautitem.e55_sequen ";
    $sqlitem .= "       left join pcprocitem                           on pcprocitem.pc81_codprocitem     = pcprocitemaut.e73_pcprocitem ";
    $sqlitem .= "       left join solicitem                            on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem ";
    $sqlitem .= "       left join liclicitem                           on liclicitem.l21_codpcprocitem    = pcprocitemaut.e73_pcprocitem ";
    $sqlitem .= "       left join pcorcamitemlic                       on pcorcamitemlic.pc26_liclicitem  = liclicitem.l21_codigo ";
    $sqlitem .= "       left join pcorcamjulg                          on pcorcamjulg.pc24_orcamitem      = pcorcamitemlic.pc26_orcamitem ";
    $sqlitem .= "                                                     and pcorcamjulg.pc24_pontuacao      = 1 ";
    $sqlitem .= "       left join pcorcamval                           on pcorcamval.pc23_orcamitem       = pcorcamjulg.pc24_orcamitem ";
    $sqlitem .= "                                                     and pcorcamval.pc23_orcamforne      = pcorcamjulg.pc24_orcamforne ";

    $sqlitem .= "  where e62_numemp = {$iSequencialEmpenho} ";
    $sqlitem .= " order by e62_sequen, o56_elemento,pc01_descrmater";

    return $sqlitem;
  }
}
