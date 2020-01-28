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

//MODULO: material
//CLASSE DA ENTIDADE matestoquetransferencia
class cl_matestoquetransferencia {
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
   var $m84_sequencial = 0;
   var $m84_matestoqueitem = 0;
   var $m84_matestoqueini = 0;
   var $m84_coddepto = 0;
   var $m84_valortotal = 0;
   var $m84_quantidade = 0;
   var $m84_transferido = 'f';
   var $m84_ativo = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 m84_sequencial = int4 = Transferência
                 m84_matestoqueitem = int8 = Sequencial do item no estoque
                 m84_matestoqueini = int8 = Movimentação do estoque
                 m84_coddepto = int4 = Código Depto. Destino
                 m84_valortotal = float4 = Valor total
                 m84_quantidade = float4 = Quantidade
                 m84_transferido = bool = Status Transferência
                 m84_ativo = bool = Situação da Transferência
                 ";
   //funcao construtor da classe
   function cl_matestoquetransferencia() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoquetransferencia");
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
       $this->m84_sequencial = ($this->m84_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m84_sequencial"]:$this->m84_sequencial);
       $this->m84_matestoqueitem = ($this->m84_matestoqueitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m84_matestoqueitem"]:$this->m84_matestoqueitem);
       $this->m84_matestoqueini = ($this->m84_matestoqueini == ""?@$GLOBALS["HTTP_POST_VARS"]["m84_matestoqueini"]:$this->m84_matestoqueini);
       $this->m84_coddepto = ($this->m84_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["m84_coddepto"]:$this->m84_coddepto);
       $this->m84_valortotal = ($this->m84_valortotal == ""?@$GLOBALS["HTTP_POST_VARS"]["m84_valortotal"]:$this->m84_valortotal);
       $this->m84_quantidade = ($this->m84_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["m84_quantidade"]:$this->m84_quantidade);
       $this->m84_transferido = ($this->m84_transferido == "f"?@$GLOBALS["HTTP_POST_VARS"]["m84_transferido"]:$this->m84_transferido);
       $this->m84_ativo = ($this->m84_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["m84_ativo"]:$this->m84_ativo);
     }else{
       $this->m84_sequencial = ($this->m84_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["m84_sequencial"]:$this->m84_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($m84_sequencial){
      $this->atualizacampos();
     if($this->m84_matestoqueitem == null ){
       $this->erro_sql = " Campo Sequencial do item no estoque nao Informado.";
       $this->erro_campo = "m84_matestoqueitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m84_matestoqueini == null ){
       $this->erro_sql = " Campo Movimentação do estoque nao Informado.";
       $this->erro_campo = "m84_matestoqueini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m84_coddepto == null ){
       $this->erro_sql = " Campo Código Depto. Destino nao Informado.";
       $this->erro_campo = "m84_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m84_valortotal == null ){
       $this->erro_sql = " Campo Valor total nao Informado.";
       $this->erro_campo = "m84_valortotal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m84_quantidade == null ){
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "m84_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m84_transferido == null ){
       $this->erro_sql = " Campo Status Transferência nao Informado.";
       $this->erro_campo = "m84_transferido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m84_ativo == null ){
       $this->erro_sql = " Campo Situação da Transferência nao Informado.";
       $this->erro_campo = "m84_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m84_sequencial == "" || $m84_sequencial == null ){
       $result = db_query("select nextval('matestoquetransferencia_m84_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoquetransferencia_m84_sequencial_seq do campo: m84_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->m84_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from matestoquetransferencia_m84_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $m84_sequencial)){
         $this->erro_sql = " Campo m84_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m84_sequencial = $m84_sequencial;
       }
     }
     if(($this->m84_sequencial == null) || ($this->m84_sequencial == "") ){
       $this->erro_sql = " Campo m84_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoquetransferencia(
                                       m84_sequencial
                                      ,m84_matestoqueitem
                                      ,m84_matestoqueini
                                      ,m84_coddepto
                                      ,m84_valortotal
                                      ,m84_quantidade
                                      ,m84_transferido
                                      ,m84_ativo
                       )
                values (
                                $this->m84_sequencial
                               ,$this->m84_matestoqueitem
                               ,$this->m84_matestoqueini
                               ,$this->m84_coddepto
                               ,$this->m84_valortotal
                               ,$this->m84_quantidade
                               ,'$this->m84_transferido'
                               ,'$this->m84_ativo'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Transferência entre depósitos ($this->m84_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Transferência entre depósitos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Transferência entre depósitos ($this->m84_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m84_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m84_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19818,'$this->m84_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3553,19818,'','".AddSlashes(pg_result($resaco,0,'m84_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3553,19819,'','".AddSlashes(pg_result($resaco,0,'m84_matestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3553,19820,'','".AddSlashes(pg_result($resaco,0,'m84_matestoqueini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3553,19821,'','".AddSlashes(pg_result($resaco,0,'m84_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3553,19822,'','".AddSlashes(pg_result($resaco,0,'m84_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3553,19823,'','".AddSlashes(pg_result($resaco,0,'m84_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3553,19824,'','".AddSlashes(pg_result($resaco,0,'m84_transferido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3553,19825,'','".AddSlashes(pg_result($resaco,0,'m84_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($m84_sequencial=null, $sWhereAlterar) {
      $this->atualizacampos();
     $sql = " update matestoquetransferencia set ";
     $virgula = "";
     if(trim($this->m84_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m84_sequencial"])){
       $sql  .= $virgula." m84_sequencial = $this->m84_sequencial ";
       $virgula = ",";
       if(trim($this->m84_sequencial) == null ){
         $this->erro_sql = " Campo Transferência nao Informado.";
         $this->erro_campo = "m84_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m84_matestoqueitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m84_matestoqueitem"])){
       $sql  .= $virgula." m84_matestoqueitem = $this->m84_matestoqueitem ";
       $virgula = ",";
       if(trim($this->m84_matestoqueitem) == null ){
         $this->erro_sql = " Campo Sequencial do item no estoque nao Informado.";
         $this->erro_campo = "m84_matestoqueitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m84_matestoqueini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m84_matestoqueini"])){
       $sql  .= $virgula." m84_matestoqueini = $this->m84_matestoqueini ";
       $virgula = ",";
       if(trim($this->m84_matestoqueini) == null ){
         $this->erro_sql = " Campo Movimentação do estoque nao Informado.";
         $this->erro_campo = "m84_matestoqueini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m84_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m84_coddepto"])){
       $sql  .= $virgula." m84_coddepto = $this->m84_coddepto ";
       $virgula = ",";
       if(trim($this->m84_coddepto) == null ){
         $this->erro_sql = " Campo Código Depto. Destino nao Informado.";
         $this->erro_campo = "m84_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m84_valortotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m84_valortotal"])){
       $sql  .= $virgula." m84_valortotal = $this->m84_valortotal ";
       $virgula = ",";
       if(trim($this->m84_valortotal) == null ){
         $this->erro_sql = " Campo Valor total nao Informado.";
         $this->erro_campo = "m84_valortotal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m84_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m84_quantidade"])){
       $sql  .= $virgula." m84_quantidade = $this->m84_quantidade ";
       $virgula = ",";
       if(trim($this->m84_quantidade) == null ){
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "m84_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m84_transferido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m84_transferido"])){
       $sql  .= $virgula." m84_transferido = '$this->m84_transferido' ";
       $virgula = ",";
       if(trim($this->m84_transferido) == null ){
         $this->erro_sql = " Campo Status Transferência nao Informado.";
         $this->erro_campo = "m84_transferido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m84_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m84_ativo"])){
       $sql  .= $virgula." m84_ativo = '$this->m84_ativo' ";
       $virgula = ",";
       if(trim($this->m84_ativo) == null ){
         $this->erro_sql = " Campo Situação da Transferência nao Informado.";
         $this->erro_campo = "m84_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m84_sequencial!=null){
       $sql .= " m84_sequencial = $this->m84_sequencial";
     } else if ($sWhereAlterar != null) {
       $sql .= " {$sWhereAlterar} ";
     }

     /**
      * Anteriormente, estava gerando acount com sequencial null, pois o sequencial nao era informado decorrente
      * do segundo parametro $sWhereAlterar, agora antes de alterar, a classe ira buscar todos os registros afetados pelo where
      * e gerar acount
      */
     if( $sWhereAlterar == null || $sWhereAlterar == "" ){
       $resaco = $this->sql_record($this->sql_query_file($m84_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$sWhereAlterar));
     }

     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

         $iValorSequencial = pg_result($resaco,$conresaco,'m84_sequencial');

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19818,'$iValorSequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m84_sequencial"]) || $this->m84_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3553,19818,'".AddSlashes($iValorSequencial)."','$this->m84_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m84_matestoqueitem"]) || $this->m84_matestoqueitem != "")
           $resac = db_query("insert into db_acount values($acount,3553,19819,'".AddSlashes(pg_result($resaco,$conresaco,'m84_matestoqueitem'))."','$this->m84_matestoqueitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m84_matestoqueini"]) || $this->m84_matestoqueini != "")
           $resac = db_query("insert into db_acount values($acount,3553,19820,'".AddSlashes(pg_result($resaco,$conresaco,'m84_matestoqueini'))."','$this->m84_matestoqueini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m84_coddepto"]) || $this->m84_coddepto != "")
           $resac = db_query("insert into db_acount values($acount,3553,19821,'".AddSlashes(pg_result($resaco,$conresaco,'m84_coddepto'))."','$this->m84_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m84_valortotal"]) || $this->m84_valortotal != "")
           $resac = db_query("insert into db_acount values($acount,3553,19822,'".AddSlashes(pg_result($resaco,$conresaco,'m84_valortotal'))."','$this->m84_valortotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m84_quantidade"]) || $this->m84_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,3553,19823,'".AddSlashes(pg_result($resaco,$conresaco,'m84_quantidade'))."','$this->m84_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m84_transferido"]) || $this->m84_transferido != "")
           $resac = db_query("insert into db_acount values($acount,3553,19824,'".AddSlashes(pg_result($resaco,$conresaco,'m84_transferido'))."','$this->m84_transferido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m84_ativo"]) || $this->m84_ativo != "")
           $resac = db_query("insert into db_acount values($acount,3553,19825,'".AddSlashes(pg_result($resaco,$conresaco,'m84_ativo'))."','$this->m84_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Transferência entre depósitos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m84_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Transferência entre depósitos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m84_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m84_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($m84_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m84_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19818,'$m84_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3553,19818,'','".AddSlashes(pg_result($resaco,$iresaco,'m84_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3553,19819,'','".AddSlashes(pg_result($resaco,$iresaco,'m84_matestoqueitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3553,19820,'','".AddSlashes(pg_result($resaco,$iresaco,'m84_matestoqueini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3553,19821,'','".AddSlashes(pg_result($resaco,$iresaco,'m84_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3553,19822,'','".AddSlashes(pg_result($resaco,$iresaco,'m84_valortotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3553,19823,'','".AddSlashes(pg_result($resaco,$iresaco,'m84_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3553,19824,'','".AddSlashes(pg_result($resaco,$iresaco,'m84_transferido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3553,19825,'','".AddSlashes(pg_result($resaco,$iresaco,'m84_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoquetransferencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m84_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m84_sequencial = $m84_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Transferência entre depósitos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m84_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Transferência entre depósitos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m84_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m84_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoquetransferencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $m84_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoquetransferencia ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoquetransferencia.m84_coddepto";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoquetransferencia.m84_matestoqueitem";
     $sql .= "      inner join matestoqueini  on  matestoqueini.m80_codigo = matestoquetransferencia.m84_matestoqueini";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join db_datausuarios  on  db_datausuarios.id_usuario = db_depart.id_usuarioresp";
     $sql .= "      inner join matestoque  as a on   a.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matestoqueini.m80_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoqueini.m80_coddepto";
     $sql .= "      inner join matestoquetipo  on  matestoquetipo.m81_codtipo = matestoqueini.m80_codtipo";
     $sql2 = "";
     if($dbwhere==""){
       if($m84_sequencial!=null ){
         $sql2 .= " where matestoquetransferencia.m84_sequencial = $m84_sequencial ";
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
   function sql_query_file ( $m84_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matestoquetransferencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($m84_sequencial!=null ){
         $sql2 .= " where matestoquetransferencia.m84_sequencial = $m84_sequencial ";
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
   function sql_query_transferencia ( $m84_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from matestoquetransferencia ";
    $sql .= "      inner join matestoqueitem on matestoqueitem.m71_codlanc       = matestoquetransferencia.m84_matestoqueitem";
    $sql .= "      inner join matestoque     on matestoqueitem.m71_codmatestoque = matestoque.m70_codigo";
    $sql .= "      inner join matestoqueini  on matestoqueini.m80_codigo         = matestoquetransferencia.m84_matestoqueini";
    $sql .= "      inner join matmater       on matmater.m60_codmater            = matestoque.m70_codmatmater";
    $sql2 = "";
    if($dbwhere==""){
      if($m84_sequencial!=null ){
        $sql2 .= " where matestoquetransferencia.m84_sequencial = $m84_sequencial ";
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
?>