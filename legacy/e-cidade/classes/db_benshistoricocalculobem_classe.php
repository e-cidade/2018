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

//MODULO: patrimonio
//CLASSE DA ENTIDADE benshistoricocalculobem
class cl_benshistoricocalculobem {
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
   var $t58_sequencial = 0;
   var $t58_benstipodepreciacao = 0;
   var $t58_benshistoricocalculo = 0;
   var $t58_bens = 0;
   var $t58_valorcalculado = 0;
   var $t58_valorresidual = 0;
   var $t58_valoranterior = 0;
   var $t58_valoratual = 0;
   var $t58_percentualdepreciado = 0;
   var $t58_vidautilanterior = 0;
   var $t58_valorresidualanterior = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 t58_sequencial = int4 = Sequencial
                 t58_benstipodepreciacao = int4 = Tipo de depreciaçao
                 t58_benshistoricocalculo = int4 = Cálculo
                 t58_bens = int4 = Bens
                 t58_valorcalculado = float4 = Valor Calculado
                 t58_valorresidual = float4 = Valor residual
                 t58_valoranterior = float4 = Valor Anterior
                 t58_valoratual = float4 = Valor Atual
                 t58_percentualdepreciado = float4 = Percentual depreciado
                 t58_vidautilanterior = int4 = Vida Útil Anterior
                 t58_valorresidualanterior = numeric(10) = Valor residual anterior
                 ";
   //funcao construtor da classe
   function cl_benshistoricocalculobem() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benshistoricocalculobem");
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
       $this->t58_sequencial = ($this->t58_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t58_sequencial"]:$this->t58_sequencial);
       $this->t58_benstipodepreciacao = ($this->t58_benstipodepreciacao == ""?@$GLOBALS["HTTP_POST_VARS"]["t58_benstipodepreciacao"]:$this->t58_benstipodepreciacao);
       $this->t58_benshistoricocalculo = ($this->t58_benshistoricocalculo == ""?@$GLOBALS["HTTP_POST_VARS"]["t58_benshistoricocalculo"]:$this->t58_benshistoricocalculo);
       $this->t58_bens = ($this->t58_bens == ""?@$GLOBALS["HTTP_POST_VARS"]["t58_bens"]:$this->t58_bens);
       $this->t58_valorcalculado = ($this->t58_valorcalculado == ""?@$GLOBALS["HTTP_POST_VARS"]["t58_valorcalculado"]:$this->t58_valorcalculado);
       $this->t58_valorresidual = ($this->t58_valorresidual == ""?@$GLOBALS["HTTP_POST_VARS"]["t58_valorresidual"]:$this->t58_valorresidual);
       $this->t58_valoranterior = ($this->t58_valoranterior == ""?@$GLOBALS["HTTP_POST_VARS"]["t58_valoranterior"]:$this->t58_valoranterior);
       $this->t58_valoratual = ($this->t58_valoratual == ""?@$GLOBALS["HTTP_POST_VARS"]["t58_valoratual"]:$this->t58_valoratual);
       $this->t58_percentualdepreciado = ($this->t58_percentualdepreciado == ""?@$GLOBALS["HTTP_POST_VARS"]["t58_percentualdepreciado"]:$this->t58_percentualdepreciado);
       $this->t58_vidautilanterior = ($this->t58_vidautilanterior == ""?@$GLOBALS["HTTP_POST_VARS"]["t58_vidautilanterior"]:$this->t58_vidautilanterior);
       $this->t58_valorresidualanterior = ($this->t58_valorresidualanterior == ""?@$GLOBALS["HTTP_POST_VARS"]["t58_valorresidualanterior"]:$this->t58_valorresidualanterior);
     }else{
       $this->t58_sequencial = ($this->t58_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t58_sequencial"]:$this->t58_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t58_sequencial){
      $this->atualizacampos();
     if($this->t58_benstipodepreciacao == null ){
       $this->erro_sql = " Campo Tipo de depreciaçao nao Informado.";
       $this->erro_campo = "t58_benstipodepreciacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t58_benshistoricocalculo == null ){
       $this->erro_sql = " Campo Cálculo nao Informado.";
       $this->erro_campo = "t58_benshistoricocalculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t58_bens == null ){
       $this->erro_sql = " Campo Bens nao Informado.";
       $this->erro_campo = "t58_bens";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t58_valorcalculado == null ){
       $this->erro_sql = " Campo Valor Calculado nao Informado.";
       $this->erro_campo = "t58_valorcalculado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t58_valorresidual == null ){
       $this->erro_sql = " Campo Valor residual nao Informado.";
       $this->erro_campo = "t58_valorresidual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t58_valoranterior == null ){
       $this->erro_sql = " Campo Valor Anterior nao Informado.";
       $this->erro_campo = "t58_valoranterior";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t58_valoratual == null ){
       $this->erro_sql = " Campo Valor Atual nao Informado.";
       $this->erro_campo = "t58_valoratual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t58_percentualdepreciado == null ){
       $this->erro_sql = " Campo Percentual depreciado nao Informado.";
       $this->erro_campo = "t58_percentualdepreciado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t58_vidautilanterior == null ){
       $this->t58_vidautilanterior = "0";
     }
     if($this->t58_valorresidualanterior == null ){
       $this->t58_valorresidualanterior = "0";
     }
     if($t58_sequencial == "" || $t58_sequencial == null ){
       $result = db_query("select nextval('benshistoricocalculobem_t58_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: benshistoricocalculobem_t58_sequencial_seq do campo: t58_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->t58_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from benshistoricocalculobem_t58_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t58_sequencial)){
         $this->erro_sql = " Campo t58_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t58_sequencial = $t58_sequencial;
       }
     }
     if(($this->t58_sequencial == null) || ($this->t58_sequencial == "") ){
       $this->erro_sql = " Campo t58_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into benshistoricocalculobem(
                                       t58_sequencial
                                      ,t58_benstipodepreciacao
                                      ,t58_benshistoricocalculo
                                      ,t58_bens
                                      ,t58_valorcalculado
                                      ,t58_valorresidual
                                      ,t58_valoranterior
                                      ,t58_valoratual
                                      ,t58_percentualdepreciado
                                      ,t58_vidautilanterior
                                      ,t58_valorresidualanterior
                       )
                values (
                                $this->t58_sequencial
                               ,$this->t58_benstipodepreciacao
                               ,$this->t58_benshistoricocalculo
                               ,$this->t58_bens
                               ,$this->t58_valorcalculado
                               ,$this->t58_valorresidual
                               ,$this->t58_valoranterior
                               ,$this->t58_valoratual
                               ,$this->t58_percentualdepreciado
                               ,$this->t58_vidautilanterior
                               ,$this->t58_valorresidualanterior
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Bens do cálculo ($this->t58_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Bens do cálculo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Bens do cálculo ($this->t58_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t58_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t58_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18580,'$this->t58_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3285,18580,'','".AddSlashes(pg_result($resaco,0,'t58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3285,18566,'','".AddSlashes(pg_result($resaco,0,'t58_benstipodepreciacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3285,18567,'','".AddSlashes(pg_result($resaco,0,'t58_benshistoricocalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3285,18568,'','".AddSlashes(pg_result($resaco,0,'t58_bens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3285,18569,'','".AddSlashes(pg_result($resaco,0,'t58_valorcalculado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3285,18570,'','".AddSlashes(pg_result($resaco,0,'t58_valorresidual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3285,18581,'','".AddSlashes(pg_result($resaco,0,'t58_valoranterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3285,18572,'','".AddSlashes(pg_result($resaco,0,'t58_valoratual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3285,18573,'','".AddSlashes(pg_result($resaco,0,'t58_percentualdepreciado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3285,19414,'','".AddSlashes(pg_result($resaco,0,'t58_vidautilanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3285,19476,'','".AddSlashes(pg_result($resaco,0,'t58_valorresidualanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($t58_sequencial=null) {
      $this->atualizacampos();
     $sql = " update benshistoricocalculobem set ";
     $virgula = "";
     if(trim($this->t58_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t58_sequencial"])){
       $sql  .= $virgula." t58_sequencial = $this->t58_sequencial ";
       $virgula = ",";
       if(trim($this->t58_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "t58_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t58_benstipodepreciacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t58_benstipodepreciacao"])){
       $sql  .= $virgula." t58_benstipodepreciacao = $this->t58_benstipodepreciacao ";
       $virgula = ",";
       if(trim($this->t58_benstipodepreciacao) == null ){
         $this->erro_sql = " Campo Tipo de depreciaçao nao Informado.";
         $this->erro_campo = "t58_benstipodepreciacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t58_benshistoricocalculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t58_benshistoricocalculo"])){
       $sql  .= $virgula." t58_benshistoricocalculo = $this->t58_benshistoricocalculo ";
       $virgula = ",";
       if(trim($this->t58_benshistoricocalculo) == null ){
         $this->erro_sql = " Campo Cálculo nao Informado.";
         $this->erro_campo = "t58_benshistoricocalculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t58_bens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t58_bens"])){
       $sql  .= $virgula." t58_bens = $this->t58_bens ";
       $virgula = ",";
       if(trim($this->t58_bens) == null ){
         $this->erro_sql = " Campo Bens nao Informado.";
         $this->erro_campo = "t58_bens";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t58_valorcalculado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t58_valorcalculado"])){
       $sql  .= $virgula." t58_valorcalculado = $this->t58_valorcalculado ";
       $virgula = ",";
       if(trim($this->t58_valorcalculado) == null ){
         $this->erro_sql = " Campo Valor Calculado nao Informado.";
         $this->erro_campo = "t58_valorcalculado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t58_valorresidual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t58_valorresidual"])){
       $sql  .= $virgula." t58_valorresidual = $this->t58_valorresidual ";
       $virgula = ",";
       if(trim($this->t58_valorresidual) == null ){
         $this->erro_sql = " Campo Valor residual nao Informado.";
         $this->erro_campo = "t58_valorresidual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t58_valoranterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t58_valoranterior"])){
       $sql  .= $virgula." t58_valoranterior = $this->t58_valoranterior ";
       $virgula = ",";
       if(trim($this->t58_valoranterior) == null ){
         $this->erro_sql = " Campo Valor Anterior nao Informado.";
         $this->erro_campo = "t58_valoranterior";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t58_valoratual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t58_valoratual"])){
       $sql  .= $virgula." t58_valoratual = $this->t58_valoratual ";
       $virgula = ",";
       if(trim($this->t58_valoratual) == null ){
         $this->erro_sql = " Campo Valor Atual nao Informado.";
         $this->erro_campo = "t58_valoratual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t58_percentualdepreciado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t58_percentualdepreciado"])){
       $sql  .= $virgula." t58_percentualdepreciado = $this->t58_percentualdepreciado ";
       $virgula = ",";
       if(trim($this->t58_percentualdepreciado) == null ){
         $this->erro_sql = " Campo Percentual depreciado nao Informado.";
         $this->erro_campo = "t58_percentualdepreciado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t58_vidautilanterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t58_vidautilanterior"])){
        if(trim($this->t58_vidautilanterior)=="" && isset($GLOBALS["HTTP_POST_VARS"]["t58_vidautilanterior"])){
           $this->t58_vidautilanterior = "0" ;
        }
       $sql  .= $virgula." t58_vidautilanterior = $this->t58_vidautilanterior ";
       $virgula = ",";
     }
     if(trim($this->t58_valorresidualanterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t58_valorresidualanterior"])){
       $sql  .= $virgula." t58_valorresidualanterior = $this->t58_valorresidualanterior ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($t58_sequencial!=null){
       $sql .= " t58_sequencial = $this->t58_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t58_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18580,'$this->t58_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t58_sequencial"]) || $this->t58_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3285,18580,'".AddSlashes(pg_result($resaco,$conresaco,'t58_sequencial'))."','$this->t58_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t58_benstipodepreciacao"]) || $this->t58_benstipodepreciacao != "")
           $resac = db_query("insert into db_acount values($acount,3285,18566,'".AddSlashes(pg_result($resaco,$conresaco,'t58_benstipodepreciacao'))."','$this->t58_benstipodepreciacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t58_benshistoricocalculo"]) || $this->t58_benshistoricocalculo != "")
           $resac = db_query("insert into db_acount values($acount,3285,18567,'".AddSlashes(pg_result($resaco,$conresaco,'t58_benshistoricocalculo'))."','$this->t58_benshistoricocalculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t58_bens"]) || $this->t58_bens != "")
           $resac = db_query("insert into db_acount values($acount,3285,18568,'".AddSlashes(pg_result($resaco,$conresaco,'t58_bens'))."','$this->t58_bens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t58_valorcalculado"]) || $this->t58_valorcalculado != "")
           $resac = db_query("insert into db_acount values($acount,3285,18569,'".AddSlashes(pg_result($resaco,$conresaco,'t58_valorcalculado'))."','$this->t58_valorcalculado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t58_valorresidual"]) || $this->t58_valorresidual != "")
           $resac = db_query("insert into db_acount values($acount,3285,18570,'".AddSlashes(pg_result($resaco,$conresaco,'t58_valorresidual'))."','$this->t58_valorresidual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t58_valoranterior"]) || $this->t58_valoranterior != "")
           $resac = db_query("insert into db_acount values($acount,3285,18581,'".AddSlashes(pg_result($resaco,$conresaco,'t58_valoranterior'))."','$this->t58_valoranterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t58_valoratual"]) || $this->t58_valoratual != "")
           $resac = db_query("insert into db_acount values($acount,3285,18572,'".AddSlashes(pg_result($resaco,$conresaco,'t58_valoratual'))."','$this->t58_valoratual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t58_percentualdepreciado"]) || $this->t58_percentualdepreciado != "")
           $resac = db_query("insert into db_acount values($acount,3285,18573,'".AddSlashes(pg_result($resaco,$conresaco,'t58_percentualdepreciado'))."','$this->t58_percentualdepreciado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t58_vidautilanterior"]) || $this->t58_vidautilanterior != "")
           $resac = db_query("insert into db_acount values($acount,3285,19414,'".AddSlashes(pg_result($resaco,$conresaco,'t58_vidautilanterior'))."','$this->t58_vidautilanterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t58_valorresidualanterior"]) || $this->t58_valorresidualanterior != "")
           $resac = db_query("insert into db_acount values($acount,3285,19476,'".AddSlashes(pg_result($resaco,$conresaco,'t58_valorresidualanterior'))."','$this->t58_valorresidualanterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bens do cálculo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t58_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bens do cálculo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($t58_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t58_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18580,'$t58_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3285,18580,'','".AddSlashes(pg_result($resaco,$iresaco,'t58_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3285,18566,'','".AddSlashes(pg_result($resaco,$iresaco,'t58_benstipodepreciacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3285,18567,'','".AddSlashes(pg_result($resaco,$iresaco,'t58_benshistoricocalculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3285,18568,'','".AddSlashes(pg_result($resaco,$iresaco,'t58_bens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3285,18569,'','".AddSlashes(pg_result($resaco,$iresaco,'t58_valorcalculado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3285,18570,'','".AddSlashes(pg_result($resaco,$iresaco,'t58_valorresidual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3285,18581,'','".AddSlashes(pg_result($resaco,$iresaco,'t58_valoranterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3285,18572,'','".AddSlashes(pg_result($resaco,$iresaco,'t58_valoratual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3285,18573,'','".AddSlashes(pg_result($resaco,$iresaco,'t58_percentualdepreciado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3285,19414,'','".AddSlashes(pg_result($resaco,$iresaco,'t58_vidautilanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3285,19476,'','".AddSlashes(pg_result($resaco,$iresaco,'t58_valorresidualanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benshistoricocalculobem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t58_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t58_sequencial = $t58_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Bens do cálculo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t58_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Bens do cálculo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t58_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t58_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:benshistoricocalculobem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $t58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from benshistoricocalculobem ";
     $sql .= "      inner join bens  on  bens.t52_bem = benshistoricocalculobem.t58_bens";
     $sql .= "       left join bensdepreciacao  on  bens.t52_bem = bensdepreciacao.t44_bens";
     $sql .= "      inner join benstipodepreciacao  on  benstipodepreciacao.t46_sequencial = benshistoricocalculobem.t58_benstipodepreciacao";
     $sql .= "      inner join benshistoricocalculo  on  benshistoricocalculo.t57_sequencial = benshistoricocalculobem.t58_benshistoricocalculo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = bens.t52_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join bensmarca  on  bensmarca.t65_sequencial = bens.t52_bensmarca";
     $sql .= "      inner join bensmodelo  on  bensmodelo.t66_sequencial = bens.t52_bensmodelo";
     $sql .= "      inner join bensmedida  on  bensmedida.t67_sequencial = bens.t52_bensmedida";
     $sql .= "      inner join db_config  as a on   a.codigo = benshistoricocalculo.t57_instituicao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = benshistoricocalculo.t57_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($t58_sequencial!=null ){
         $sql2 .= " where benshistoricocalculobem.t58_sequencial = $t58_sequencial ";
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
   function sql_query_file ( $t58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from benshistoricocalculobem ";
     $sql2 = "";
     if($dbwhere==""){
       if($t58_sequencial!=null ){
         $sql2 .= " where benshistoricocalculobem.t58_sequencial = $t58_sequencial ";
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
   function sql_query_calculo ( $t58_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     } else {
       $sql .= $campos;
     }
     $sql .= " from benshistoricocalculobem ";
     $sql .= "      inner join benshistoricocalculo  on  benshistoricocalculo.t57_sequencial = benshistoricocalculobem.t58_benshistoricocalculo";
     $sql2 = "";
     if($dbwhere==""){
       if($t58_sequencial!=null ){
         $sql2 .= " where benshistoricocalculobem.t58_sequencial = $t58_sequencial ";
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