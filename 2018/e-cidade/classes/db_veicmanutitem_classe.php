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

//MODULO: veiculos
//CLASSE DA ENTIDADE veicmanutitem
class cl_veicmanutitem {
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
   var $ve63_codigo = 0;
   var $ve63_veicmanut = 0;
   var $ve63_descr = null;
   var $ve63_quant = 0;
   var $ve63_vlruni = 0;
   var $ve63_valortotalcomdesconto = 0;
   var $ve63_unidade = 0;
   var $ve63_tipoitem = 0;
   var $ve63_proximatroca = 0;
   var $ve63_datanota_dia = null;
   var $ve63_datanota_mes = null;
   var $ve63_datanota_ano = null;
   var $ve63_datanota = null;
   var $ve63_numeronota = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ve63_codigo = int4 = Código Seq.
                 ve63_veicmanut = int4 = Manutenção
                 ve63_descr = varchar(40) = Descrição
                 ve63_quant = float8 = Quantidade
                 ve63_vlruni = float8 = Valor Unitário
                 ve63_valortotalcomdesconto = float8 = Valor total com desconto
                 ve63_unidade = int4 = Unidade
                 ve63_tipoitem = int4 = Tipo de Item
                 ve63_proximatroca = float8 = Próxima Troca
                 ve63_datanota = date = Data da Nota
                 ve63_numeronota = varchar(10) = Número da Nota
                 ";
   //funcao construtor da classe
   function cl_veicmanutitem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veicmanutitem");
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
       $this->ve63_codigo = ($this->ve63_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_codigo"]:$this->ve63_codigo);
       $this->ve63_veicmanut = ($this->ve63_veicmanut == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_veicmanut"]:$this->ve63_veicmanut);
       $this->ve63_descr = ($this->ve63_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_descr"]:$this->ve63_descr);
       $this->ve63_quant = ($this->ve63_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_quant"]:$this->ve63_quant);
       $this->ve63_vlruni = ($this->ve63_vlruni == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_vlruni"]:$this->ve63_vlruni);
       $this->ve63_valortotalcomdesconto = ($this->ve63_valortotalcomdesconto == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_valortotalcomdesconto"]:$this->ve63_valortotalcomdesconto);
       $this->ve63_unidade = ($this->ve63_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_unidade"]:$this->ve63_unidade);
       $this->ve63_tipoitem = ($this->ve63_tipoitem == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_tipoitem"]:$this->ve63_tipoitem);
       $this->ve63_proximatroca = ($this->ve63_proximatroca == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_proximatroca"]:$this->ve63_proximatroca);
       if($this->ve63_datanota == ""){
         $this->ve63_datanota_dia = ($this->ve63_datanota_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_datanota_dia"]:$this->ve63_datanota_dia);
         $this->ve63_datanota_mes = ($this->ve63_datanota_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_datanota_mes"]:$this->ve63_datanota_mes);
         $this->ve63_datanota_ano = ($this->ve63_datanota_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_datanota_ano"]:$this->ve63_datanota_ano);
         if($this->ve63_datanota_dia != ""){
            $this->ve63_datanota = $this->ve63_datanota_ano."-".$this->ve63_datanota_mes."-".$this->ve63_datanota_dia;
         }
       }
       $this->ve63_numeronota = ($this->ve63_numeronota == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_numeronota"]:$this->ve63_numeronota);
     }else{
       $this->ve63_codigo = ($this->ve63_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ve63_codigo"]:$this->ve63_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ve63_codigo){
      $this->atualizacampos();
     if($this->ve63_veicmanut == null ){
       $this->erro_sql = " Campo Manutenção não informado.";
       $this->erro_campo = "ve63_veicmanut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve63_descr == null ){
       $this->erro_sql = " Campo Descrição é de preenchimento obrigatório.";
       $this->erro_campo = "ve63_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve63_quant == null ){
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "ve63_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve63_vlruni == null ){
       $this->erro_sql = " Campo Valor Unitário não informado.";
       $this->erro_campo = "ve63_vlruni";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve63_valortotalcomdesconto == null ){
       $this->erro_sql = " Campo Valor total com desconto não informado.";
       $this->erro_campo = "ve63_valortotalcomdesconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve63_unidade == null ){
       $this->erro_sql = " Campo Unidade não informado.";
       $this->erro_campo = "ve63_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve63_tipoitem == null ){
       $this->erro_sql = " Campo Tipo de Item não informado.";
       $this->erro_campo = "ve63_tipoitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve63_proximatroca == null ){
       $this->ve63_proximatroca = "null";
     }
     if($this->ve63_datanota == null ){
       $this->ve63_datanota = "null";
     }
     if($ve63_codigo == "" || $ve63_codigo == null ){
       $result = db_query("select nextval('veicmanutitem_ve63_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veicmanutitem_ve63_codigo_seq do campo: ve63_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ve63_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from veicmanutitem_ve63_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve63_codigo)){
         $this->erro_sql = " Campo ve63_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve63_codigo = $ve63_codigo;
       }
     }
     if(($this->ve63_codigo == null) || ($this->ve63_codigo == "") ){
       $this->erro_sql = " Campo ve63_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veicmanutitem(
                                       ve63_codigo
                                      ,ve63_veicmanut
                                      ,ve63_descr
                                      ,ve63_quant
                                      ,ve63_vlruni
                                      ,ve63_valortotalcomdesconto
                                      ,ve63_unidade
                                      ,ve63_tipoitem
                                      ,ve63_proximatroca
                                      ,ve63_datanota
                                      ,ve63_numeronota
                       )
                values (
                                $this->ve63_codigo
                               ,$this->ve63_veicmanut
                               ,'$this->ve63_descr'
                               ,$this->ve63_quant
                               ,$this->ve63_vlruni
                               ,$this->ve63_valortotalcomdesconto
                               ,$this->ve63_unidade
                               ,$this->ve63_tipoitem
                               ,$this->ve63_proximatroca
                               ,".($this->ve63_datanota == "null" || $this->ve63_datanota == ""?"null":"'".$this->ve63_datanota."'")."
                               ,'$this->ve63_numeronota'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens da manutenção dos veículos ($this->ve63_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens da manutenção dos veículos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens da manutenção dos veículos ($this->ve63_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve63_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ve63_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9338,'$this->ve63_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1604,9338,'','".AddSlashes(pg_result($resaco,0,'ve63_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,9339,'','".AddSlashes(pg_result($resaco,0,'ve63_veicmanut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,9340,'','".AddSlashes(pg_result($resaco,0,'ve63_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,9341,'','".AddSlashes(pg_result($resaco,0,'ve63_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,9342,'','".AddSlashes(pg_result($resaco,0,'ve63_vlruni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,21354,'','".AddSlashes(pg_result($resaco,0,'ve63_valortotalcomdesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,21355,'','".AddSlashes(pg_result($resaco,0,'ve63_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,21356,'','".AddSlashes(pg_result($resaco,0,'ve63_tipoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,21495,'','".AddSlashes(pg_result($resaco,0,'ve63_proximatroca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,21496,'','".AddSlashes(pg_result($resaco,0,'ve63_datanota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1604,21497,'','".AddSlashes(pg_result($resaco,0,'ve63_numeronota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ve63_codigo=null) {
      $this->atualizacampos();
     $sql = " update veicmanutitem set ";
     $virgula = "";
     if(trim($this->ve63_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_codigo"])){
       $sql  .= $virgula." ve63_codigo = $this->ve63_codigo ";
       $virgula = ",";
       if(trim($this->ve63_codigo) == null ){
         $this->erro_sql = " Campo Código Seq. não informado.";
         $this->erro_campo = "ve63_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve63_veicmanut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_veicmanut"])){
       $sql  .= $virgula." ve63_veicmanut = $this->ve63_veicmanut ";
       $virgula = ",";
       if(trim($this->ve63_veicmanut) == null ){
         $this->erro_sql = " Campo Manutenção não informado.";
         $this->erro_campo = "ve63_veicmanut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve63_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_descr"])){
       $sql  .= $virgula." ve63_descr = '$this->ve63_descr' ";
       $virgula = ",";
       if(trim($this->ve63_descr) == null ){
         $this->erro_sql = " Campo Descrição é de preenchimento obrigatório.";
         $this->erro_campo = "ve63_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve63_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_quant"])){
       $sql  .= $virgula." ve63_quant = $this->ve63_quant ";
       $virgula = ",";
       if(trim($this->ve63_quant) == null ){
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "ve63_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve63_vlruni)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_vlruni"])){
       $sql  .= $virgula." ve63_vlruni = $this->ve63_vlruni ";
       $virgula = ",";
       if(trim($this->ve63_vlruni) == null ){
         $this->erro_sql = " Campo Valor Unitário não informado.";
         $this->erro_campo = "ve63_vlruni";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve63_valortotalcomdesconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_valortotalcomdesconto"])){
       $sql  .= $virgula." ve63_valortotalcomdesconto = $this->ve63_valortotalcomdesconto ";
       $virgula = ",";
       if(trim($this->ve63_valortotalcomdesconto) == null ){
         $this->erro_sql = " Campo Valor total com desconto não informado.";
         $this->erro_campo = "ve63_valortotalcomdesconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve63_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_unidade"])){
       $sql  .= $virgula." ve63_unidade = $this->ve63_unidade ";
       $virgula = ",";
       if(trim($this->ve63_unidade) == null ){
         $this->erro_sql = " Campo Unidade não informado.";
         $this->erro_campo = "ve63_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve63_tipoitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_tipoitem"])){
       $sql  .= $virgula." ve63_tipoitem = $this->ve63_tipoitem ";
       $virgula = ",";
       if(trim($this->ve63_tipoitem) == null ){
         $this->erro_sql = " Campo Tipo de Item não informado.";
         $this->erro_campo = "ve63_tipoitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve63_proximatroca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_proximatroca"])){
        if(trim($this->ve63_proximatroca)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ve63_proximatroca"])){
           $this->ve63_proximatroca = "0" ;
        }
       $sql  .= $virgula." ve63_proximatroca = $this->ve63_proximatroca ";
       $virgula = ",";
     }
     if(trim($this->ve63_datanota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_datanota_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ve63_datanota_dia"] !="") ){
       $sql  .= $virgula." ve63_datanota = '$this->ve63_datanota' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ve63_datanota_dia"])){
         $sql  .= $virgula." ve63_datanota = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ve63_numeronota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve63_numeronota"])){
       $sql  .= $virgula." ve63_numeronota = '$this->ve63_numeronota' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ve63_codigo!=null){
       $sql .= " ve63_codigo = $this->ve63_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ve63_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,9338,'$this->ve63_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve63_codigo"]) || $this->ve63_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1604,9338,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_codigo'))."','$this->ve63_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve63_veicmanut"]) || $this->ve63_veicmanut != "")
             $resac = db_query("insert into db_acount values($acount,1604,9339,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_veicmanut'))."','$this->ve63_veicmanut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve63_descr"]) || $this->ve63_descr != "")
             $resac = db_query("insert into db_acount values($acount,1604,9340,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_descr'))."','$this->ve63_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve63_quant"]) || $this->ve63_quant != "")
             $resac = db_query("insert into db_acount values($acount,1604,9341,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_quant'))."','$this->ve63_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve63_vlruni"]) || $this->ve63_vlruni != "")
             $resac = db_query("insert into db_acount values($acount,1604,9342,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_vlruni'))."','$this->ve63_vlruni',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve63_valortotalcomdesconto"]) || $this->ve63_valortotalcomdesconto != "")
             $resac = db_query("insert into db_acount values($acount,1604,21354,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_valortotalcomdesconto'))."','$this->ve63_valortotalcomdesconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve63_unidade"]) || $this->ve63_unidade != "")
             $resac = db_query("insert into db_acount values($acount,1604,21355,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_unidade'))."','$this->ve63_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve63_tipoitem"]) || $this->ve63_tipoitem != "")
             $resac = db_query("insert into db_acount values($acount,1604,21356,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_tipoitem'))."','$this->ve63_tipoitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve63_proximatroca"]) || $this->ve63_proximatroca != "")
             $resac = db_query("insert into db_acount values($acount,1604,21495,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_proximatroca'))."','$this->ve63_proximatroca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve63_datanota"]) || $this->ve63_datanota != "")
             $resac = db_query("insert into db_acount values($acount,1604,21496,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_datanota'))."','$this->ve63_datanota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ve63_numeronota"]) || $this->ve63_numeronota != "")
             $resac = db_query("insert into db_acount values($acount,1604,21497,'".AddSlashes(pg_result($resaco,$conresaco,'ve63_numeronota'))."','$this->ve63_numeronota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da manutenção dos veículos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve63_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Itens da manutenção dos veículos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve63_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve63_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ve63_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ve63_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,9338,'$ve63_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1604,9338,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1604,9339,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_veicmanut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1604,9340,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1604,9341,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1604,9342,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_vlruni'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1604,21354,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_valortotalcomdesconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1604,21355,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1604,21356,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_tipoitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1604,21495,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_proximatroca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1604,21496,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_datanota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1604,21497,'','".AddSlashes(pg_result($resaco,$iresaco,'ve63_numeronota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from veicmanutitem
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ve63_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ve63_codigo = $ve63_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens da manutenção dos veículos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve63_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Itens da manutenção dos veículos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve63_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve63_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:veicmanutitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ve63_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from veicmanutitem ";
     $sql .= "      inner join veicmanut  on  veicmanut.ve62_codigo = veicmanutitem.ve63_veicmanut";
     $sql .= "      inner join veiccadtiposervico  on  veiccadtiposervico.ve28_codigo = veicmanut.ve62_veiccadtiposervico";
     $sql .= "      inner join veiculos  on  veiculos.ve01_codigo = veicmanut.ve62_veiculos";
     $sql .= "      left  join matunid  on  matunid.m61_codmatunid = veicmanutitem.ve63_unidade";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ve63_codigo)) {
         $sql2 .= " where veicmanutitem.ve63_codigo = $ve63_codigo ";
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
   public function sql_query_file ($ve63_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from veicmanutitem ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ve63_codigo)){
         $sql2 .= " where veicmanutitem.ve63_codigo = $ve63_codigo ";
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

   function sql_query_pcmater ( $ve63_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from veicmanutitem ";
     $sql .= "      inner join veicmanut  on  veicmanut.ve62_codigo = veicmanutitem.ve63_veicmanut";
     $sql .= "      inner join veiccadtiposervico  on  veiccadtiposervico.ve28_codigo = veicmanut.ve62_veiccadtiposervico";
     $sql .= "      left join veicmanutitempcmater on ve64_veicmanutitem = ve63_codigo";
     $sql .= "      left join pcmater on ve64_pcmater = pc01_codmater";
     $sql .= "      left join matunid on ve63_unidade = m61_codmatunid";
     $sql2 = "";
     if($dbwhere==""){
       if($ve63_codigo!=null ){
         $sql2 .= " where veicmanutitem.ve63_codigo = $ve63_codigo ";
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

 function sql_query_info ( $ve62_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
   }

   /**
   * Retorna os Itens da Manutenção
   *
   * @param integer $ve62_codigo
   * @param String  $campos
   * @param String  $ordem
   * @param String  $dbwhere
   * @return String
   */
  function sql_query_ItensManutencao ( $ve62_codigo=null, $campos="*", $ordem=null, $dbwhere=""){
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
     $sql .= " from veicmanut ";
     $sql .= " inner join veicmanutitem on ve63_veicmanut = ve62_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve62_codigo!=null ){
         $sql2 .= " where veicmanut.ve62_codigo = $ve62_codigo ";
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
