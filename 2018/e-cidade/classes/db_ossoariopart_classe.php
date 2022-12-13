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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE ossoariopart
class cl_ossoariopart {
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
   var $cm02_i_codigo = 0;
   var $cm02_i_processo = 0;
   var $cm02_i_proprietario = 0;
   var $cm02_c_quadra = null;
   var $cm02_i_lote = 0;
   var $cm02_f_metragem1 = 0;
   var $cm02_f_metragem2 = 0;
   var $cm02_d_aquisicao_dia = null;
   var $cm02_d_aquisicao_mes = null;
   var $cm02_d_aquisicao_ano = null;
   var $cm02_d_aquisicao = null;
   var $cm02_d_entrada_dia = null;
   var $cm02_d_entrada_mes = null;
   var $cm02_d_entrada_ano = null;
   var $cm02_d_entrada = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cm02_i_codigo = int4 = Código
                 cm02_i_processo = int4 = Processo
                 cm02_i_proprietario = int4 = Proprietário
                 cm02_c_quadra = char(3) = Quadra
                 cm02_i_lote = int4 = Lote
                 cm02_f_metragem1 = float8 = Metragem 1
                 cm02_f_metragem2 = float8 = Metragem 2
                 cm02_d_aquisicao = date = Aquisição
                 cm02_d_entrada = date = Entrada
                 ";
   //funcao construtor da classe
   function cl_ossoariopart() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("ossoariopart");
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
       $this->cm02_i_codigo = ($this->cm02_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_i_codigo"]:$this->cm02_i_codigo);
       $this->cm02_i_processo = ($this->cm02_i_processo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_i_processo"]:$this->cm02_i_processo);
       $this->cm02_i_proprietario = ($this->cm02_i_proprietario == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_i_proprietario"]:$this->cm02_i_proprietario);
       $this->cm02_c_quadra = ($this->cm02_c_quadra == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_c_quadra"]:$this->cm02_c_quadra);
       $this->cm02_i_lote = ($this->cm02_i_lote == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_i_lote"]:$this->cm02_i_lote);
       $this->cm02_f_metragem1 = ($this->cm02_f_metragem1 == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_f_metragem1"]:$this->cm02_f_metragem1);
       $this->cm02_f_metragem2 = ($this->cm02_f_metragem2 == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_f_metragem2"]:$this->cm02_f_metragem2);
       if($this->cm02_d_aquisicao == ""){
         $this->cm02_d_aquisicao_dia = ($this->cm02_d_aquisicao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_d_aquisicao_dia"]:$this->cm02_d_aquisicao_dia);
         $this->cm02_d_aquisicao_mes = ($this->cm02_d_aquisicao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_d_aquisicao_mes"]:$this->cm02_d_aquisicao_mes);
         $this->cm02_d_aquisicao_ano = ($this->cm02_d_aquisicao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_d_aquisicao_ano"]:$this->cm02_d_aquisicao_ano);
         if($this->cm02_d_aquisicao_dia != ""){
            $this->cm02_d_aquisicao = $this->cm02_d_aquisicao_ano."-".$this->cm02_d_aquisicao_mes."-".$this->cm02_d_aquisicao_dia;
         }
       }
       if($this->cm02_d_entrada == ""){
         $this->cm02_d_entrada_dia = ($this->cm02_d_entrada_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_d_entrada_dia"]:$this->cm02_d_entrada_dia);
         $this->cm02_d_entrada_mes = ($this->cm02_d_entrada_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_d_entrada_mes"]:$this->cm02_d_entrada_mes);
         $this->cm02_d_entrada_ano = ($this->cm02_d_entrada_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_d_entrada_ano"]:$this->cm02_d_entrada_ano);
         if($this->cm02_d_entrada_dia != ""){
            $this->cm02_d_entrada = $this->cm02_d_entrada_ano."-".$this->cm02_d_entrada_mes."-".$this->cm02_d_entrada_dia;
         }
       }
     }else{
       $this->cm02_i_codigo = ($this->cm02_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm02_i_codigo"]:$this->cm02_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm02_i_codigo){
      $this->atualizacampos();
     if($this->cm02_i_processo == null ){
       $this->erro_sql = " Campo Processo nao Informado.";
       $this->erro_campo = "cm02_i_processo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm02_i_proprietario == null ){
       $this->erro_sql = " Campo Proprietário nao Informado.";
       $this->erro_campo = "cm02_i_proprietario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm02_c_quadra == null ){
       $this->erro_sql = " Campo Quadra nao Informado.";
       $this->erro_campo = "cm02_c_quadra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm02_i_lote == null ){
       $this->erro_sql = " Campo Lote nao Informado.";
       $this->erro_campo = "cm02_i_lote";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm02_f_metragem1 == null ){
       $this->erro_sql = " Campo Metragem 1 nao Informado.";
       $this->erro_campo = "cm02_f_metragem1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm02_f_metragem2 == null ){
       $this->erro_sql = " Campo Metragem 2 nao Informado.";
       $this->erro_campo = "cm02_f_metragem2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm02_d_aquisicao == null ){
       $this->erro_sql = " Campo Aquisição nao Informado.";
       $this->erro_campo = "cm02_d_aquisicao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm02_d_entrada == null ){
       $this->erro_sql = " Campo Entrada nao Informado.";
       $this->erro_campo = "cm02_d_entrada_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm02_i_codigo == "" || $cm02_i_codigo == null ){
       $result = db_query("select nextval('ossoariopart_cm02_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: ossoariopart_cm02_i_codigo_seq do campo: cm02_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cm02_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from ossoariopart_cm02_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm02_i_codigo)){
         $this->erro_sql = " Campo cm02_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm02_i_codigo = $cm02_i_codigo;
       }
     }
     if(($this->cm02_i_codigo == null) || ($this->cm02_i_codigo == "") ){
       $this->erro_sql = " Campo cm02_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into ossoariopart(
                                       cm02_i_codigo
                                      ,cm02_i_processo
                                      ,cm02_i_proprietario
                                      ,cm02_c_quadra
                                      ,cm02_i_lote
                                      ,cm02_f_metragem1
                                      ,cm02_f_metragem2
                                      ,cm02_d_aquisicao
                                      ,cm02_d_entrada
                       )
                values (
                                $this->cm02_i_codigo
                               ,$this->cm02_i_processo
                               ,$this->cm02_i_proprietario
                               ,'$this->cm02_c_quadra'
                               ,$this->cm02_i_lote
                               ,$this->cm02_f_metragem1
                               ,$this->cm02_f_metragem2
                               ,".($this->cm02_d_aquisicao == "null" || $this->cm02_d_aquisicao == ""?"null":"'".$this->cm02_d_aquisicao."'")."
                               ,".($this->cm02_d_entrada == "null" || $this->cm02_d_entrada == ""?"null":"'".$this->cm02_d_entrada."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ossário Particular ($this->cm02_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ossário Particular já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ossário Particular ($this->cm02_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm02_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm02_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10359,'$this->cm02_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1793,10359,'','".AddSlashes(pg_result($resaco,0,'cm02_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1793,10360,'','".AddSlashes(pg_result($resaco,0,'cm02_i_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1793,10361,'','".AddSlashes(pg_result($resaco,0,'cm02_i_proprietario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1793,10362,'','".AddSlashes(pg_result($resaco,0,'cm02_c_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1793,10363,'','".AddSlashes(pg_result($resaco,0,'cm02_i_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1793,10364,'','".AddSlashes(pg_result($resaco,0,'cm02_f_metragem1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1793,10365,'','".AddSlashes(pg_result($resaco,0,'cm02_f_metragem2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1793,10366,'','".AddSlashes(pg_result($resaco,0,'cm02_d_aquisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1793,10367,'','".AddSlashes(pg_result($resaco,0,'cm02_d_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($cm02_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update ossoariopart set ";
     $virgula = "";
     if(trim($this->cm02_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm02_i_codigo"])){
       $sql  .= $virgula." cm02_i_codigo = $this->cm02_i_codigo ";
       $virgula = ",";
       if(trim($this->cm02_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm02_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm02_i_processo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm02_i_processo"])){
       $sql  .= $virgula." cm02_i_processo = $this->cm02_i_processo ";
       $virgula = ",";
       if(trim($this->cm02_i_processo) == null ){
         $this->erro_sql = " Campo Processo nao Informado.";
         $this->erro_campo = "cm02_i_processo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm02_i_proprietario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm02_i_proprietario"])){
       $sql  .= $virgula." cm02_i_proprietario = $this->cm02_i_proprietario ";
       $virgula = ",";
       if(trim($this->cm02_i_proprietario) == null ){
         $this->erro_sql = " Campo Proprietário nao Informado.";
         $this->erro_campo = "cm02_i_proprietario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm02_c_quadra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm02_c_quadra"])){
       $sql  .= $virgula." cm02_c_quadra = '$this->cm02_c_quadra' ";
       $virgula = ",";
       if(trim($this->cm02_c_quadra) == null ){
         $this->erro_sql = " Campo Quadra nao Informado.";
         $this->erro_campo = "cm02_c_quadra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm02_i_lote)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm02_i_lote"])){
       $sql  .= $virgula." cm02_i_lote = $this->cm02_i_lote ";
       $virgula = ",";
       if(trim($this->cm02_i_lote) == null ){
         $this->erro_sql = " Campo Lote nao Informado.";
         $this->erro_campo = "cm02_i_lote";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm02_f_metragem1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm02_f_metragem1"])){
       $sql  .= $virgula." cm02_f_metragem1 = $this->cm02_f_metragem1 ";
       $virgula = ",";
       if(trim($this->cm02_f_metragem1) == null ){
         $this->erro_sql = " Campo Metragem 1 nao Informado.";
         $this->erro_campo = "cm02_f_metragem1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm02_f_metragem2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm02_f_metragem2"])){
       $sql  .= $virgula." cm02_f_metragem2 = $this->cm02_f_metragem2 ";
       $virgula = ",";
       if(trim($this->cm02_f_metragem2) == null ){
         $this->erro_sql = " Campo Metragem 2 nao Informado.";
         $this->erro_campo = "cm02_f_metragem2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm02_d_aquisicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm02_d_aquisicao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm02_d_aquisicao_dia"] !="") ){
       $sql  .= $virgula." cm02_d_aquisicao = '$this->cm02_d_aquisicao' ";
       $virgula = ",";
       if(trim($this->cm02_d_aquisicao) == null ){
         $this->erro_sql = " Campo Aquisição nao Informado.";
         $this->erro_campo = "cm02_d_aquisicao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm02_d_aquisicao_dia"])){
         $sql  .= $virgula." cm02_d_aquisicao = null ";
         $virgula = ",";
         if(trim($this->cm02_d_aquisicao) == null ){
           $this->erro_sql = " Campo Aquisição nao Informado.";
           $this->erro_campo = "cm02_d_aquisicao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm02_d_entrada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm02_d_entrada_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm02_d_entrada_dia"] !="") ){
       $sql  .= $virgula." cm02_d_entrada = '$this->cm02_d_entrada' ";
       $virgula = ",";
       if(trim($this->cm02_d_entrada) == null ){
         $this->erro_sql = " Campo Entrada nao Informado.";
         $this->erro_campo = "cm02_d_entrada_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm02_d_entrada_dia"])){
         $sql  .= $virgula." cm02_d_entrada = null ";
         $virgula = ",";
         if(trim($this->cm02_d_entrada) == null ){
           $this->erro_sql = " Campo Entrada nao Informado.";
           $this->erro_campo = "cm02_d_entrada_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($cm02_i_codigo!=null){
       $sql .= " cm02_i_codigo = $this->cm02_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm02_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10359,'$this->cm02_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm02_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1793,10359,'".AddSlashes(pg_result($resaco,$conresaco,'cm02_i_codigo'))."','$this->cm02_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm02_i_processo"]))
           $resac = db_query("insert into db_acount values($acount,1793,10360,'".AddSlashes(pg_result($resaco,$conresaco,'cm02_i_processo'))."','$this->cm02_i_processo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm02_i_proprietario"]))
           $resac = db_query("insert into db_acount values($acount,1793,10361,'".AddSlashes(pg_result($resaco,$conresaco,'cm02_i_proprietario'))."','$this->cm02_i_proprietario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm02_c_quadra"]))
           $resac = db_query("insert into db_acount values($acount,1793,10362,'".AddSlashes(pg_result($resaco,$conresaco,'cm02_c_quadra'))."','$this->cm02_c_quadra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm02_i_lote"]))
           $resac = db_query("insert into db_acount values($acount,1793,10363,'".AddSlashes(pg_result($resaco,$conresaco,'cm02_i_lote'))."','$this->cm02_i_lote',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm02_f_metragem1"]))
           $resac = db_query("insert into db_acount values($acount,1793,10364,'".AddSlashes(pg_result($resaco,$conresaco,'cm02_f_metragem1'))."','$this->cm02_f_metragem1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm02_f_metragem2"]))
           $resac = db_query("insert into db_acount values($acount,1793,10365,'".AddSlashes(pg_result($resaco,$conresaco,'cm02_f_metragem2'))."','$this->cm02_f_metragem2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm02_d_aquisicao"]))
           $resac = db_query("insert into db_acount values($acount,1793,10366,'".AddSlashes(pg_result($resaco,$conresaco,'cm02_d_aquisicao'))."','$this->cm02_d_aquisicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm02_d_entrada"]))
           $resac = db_query("insert into db_acount values($acount,1793,10367,'".AddSlashes(pg_result($resaco,$conresaco,'cm02_d_entrada'))."','$this->cm02_d_entrada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ossário Particular nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm02_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ossário Particular nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($cm02_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm02_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10359,'$cm02_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1793,10359,'','".AddSlashes(pg_result($resaco,$iresaco,'cm02_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1793,10360,'','".AddSlashes(pg_result($resaco,$iresaco,'cm02_i_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1793,10361,'','".AddSlashes(pg_result($resaco,$iresaco,'cm02_i_proprietario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1793,10362,'','".AddSlashes(pg_result($resaco,$iresaco,'cm02_c_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1793,10363,'','".AddSlashes(pg_result($resaco,$iresaco,'cm02_i_lote'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1793,10364,'','".AddSlashes(pg_result($resaco,$iresaco,'cm02_f_metragem1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1793,10365,'','".AddSlashes(pg_result($resaco,$iresaco,'cm02_f_metragem2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1793,10366,'','".AddSlashes(pg_result($resaco,$iresaco,'cm02_d_aquisicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1793,10367,'','".AddSlashes(pg_result($resaco,$iresaco,'cm02_d_entrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from ossoariopart
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm02_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm02_i_codigo = $cm02_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ossário Particular nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm02_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ossário Particular nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm02_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:ossoariopart";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $cm02_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ossoariopart ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = ossoariopart.cm02_i_proprietario";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = ossoariopart.cm02_i_processo";
     $sql .= "      inner join cgm as cgm1 on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($cm02_i_codigo!=null ){
         $sql2 .= " where ossoariopart.cm02_i_codigo = $cm02_i_codigo ";
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
   function sql_query_file ( $cm02_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from ossoariopart ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm02_i_codigo!=null ){
         $sql2 .= " where ossoariopart.cm02_i_codigo = $cm02_i_codigo ";
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