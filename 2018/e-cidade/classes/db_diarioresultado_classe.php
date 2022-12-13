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

//MODULO: escola
//CLASSE DA ENTIDADE diarioresultado
class cl_diarioresultado {
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
   var $ed73_i_codigo = 0;
   var $ed73_i_diario = 0;
   var $ed73_i_procresultado = 0;
   var $ed73_i_valornota = 0;
   var $ed73_c_valorconceito = null;
   var $ed73_t_parecer = null;
   var $ed73_c_aprovmin = null;
   var $ed73_c_amparo = null;
   var $ed73_valorreal = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed73_i_codigo = int8 = Código
                 ed73_i_diario = int8 = Diário de Classe
                 ed73_i_procresultado = int8 = Resultado
                 ed73_i_valornota = float8 = Nota
                 ed73_c_valorconceito = char(3) = Conceito
                 ed73_t_parecer = text = Parecer
                 ed73_c_aprovmin = char(1) = Aproveitamento Mínimo
                 ed73_c_amparo = char(1) = Amparo
                 ed73_valorreal = float8 = Valor Real
                 ";
   //funcao construtor da classe
   function cl_diarioresultado() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diarioresultado");
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
       $this->ed73_i_codigo = ($this->ed73_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed73_i_codigo"]:$this->ed73_i_codigo);
       $this->ed73_i_diario = ($this->ed73_i_diario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed73_i_diario"]:$this->ed73_i_diario);
       $this->ed73_i_procresultado = ($this->ed73_i_procresultado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed73_i_procresultado"]:$this->ed73_i_procresultado);
       $this->ed73_i_valornota = ($this->ed73_i_valornota == ""?@$GLOBALS["HTTP_POST_VARS"]["ed73_i_valornota"]:$this->ed73_i_valornota);
       $this->ed73_c_valorconceito = ($this->ed73_c_valorconceito == ""?@$GLOBALS["HTTP_POST_VARS"]["ed73_c_valorconceito"]:$this->ed73_c_valorconceito);
       $this->ed73_t_parecer = ($this->ed73_t_parecer == ""?@$GLOBALS["HTTP_POST_VARS"]["ed73_t_parecer"]:$this->ed73_t_parecer);
       $this->ed73_c_aprovmin = ($this->ed73_c_aprovmin == ""?@$GLOBALS["HTTP_POST_VARS"]["ed73_c_aprovmin"]:$this->ed73_c_aprovmin);
       $this->ed73_c_amparo = ($this->ed73_c_amparo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed73_c_amparo"]:$this->ed73_c_amparo);
       $this->ed73_valorreal = ($this->ed73_valorreal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed73_valorreal"]:$this->ed73_valorreal);
     }else{
       $this->ed73_i_codigo = ($this->ed73_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed73_i_codigo"]:$this->ed73_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ed73_i_codigo){
      $this->atualizacampos();
     if($this->ed73_i_diario == null ){
       $this->erro_sql = " Campo Diário de Classe não informado.";
       $this->erro_campo = "ed73_i_diario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed73_i_procresultado == null ){
       $this->erro_sql = " Campo Resultado não informado.";
       $this->erro_campo = "ed73_i_procresultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed73_i_valornota == null ){
       $this->ed73_i_valornota = "null";
     }
     if($this->ed73_c_aprovmin == null ){
       $this->erro_sql = " Campo Aproveitamento Mínimo não informado.";
       $this->erro_campo = "ed73_c_aprovmin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed73_c_amparo == null ){
       $this->ed73_c_amparo = "N";
     }
     if($this->ed73_valorreal == null ){
       $this->ed73_valorreal = "0";
     }
     if($ed73_i_codigo == "" || $ed73_i_codigo == null ){
       $result = db_query("select nextval('diarioresultado_ed73_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diarioresultado_ed73_i_codigo_seq do campo: ed73_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed73_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from diarioresultado_ed73_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed73_i_codigo)){
         $this->erro_sql = " Campo ed73_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed73_i_codigo = $ed73_i_codigo;
       }
     }
     if(($this->ed73_i_codigo == null) || ($this->ed73_i_codigo == "") ){
       $this->erro_sql = " Campo ed73_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diarioresultado(
                                       ed73_i_codigo
                                      ,ed73_i_diario
                                      ,ed73_i_procresultado
                                      ,ed73_i_valornota
                                      ,ed73_c_valorconceito
                                      ,ed73_t_parecer
                                      ,ed73_c_aprovmin
                                      ,ed73_c_amparo
                                      ,ed73_valorreal
                       )
                values (
                                $this->ed73_i_codigo
                               ,$this->ed73_i_diario
                               ,$this->ed73_i_procresultado
                               ,$this->ed73_i_valornota
                               ,'$this->ed73_c_valorconceito'
                               ,'$this->ed73_t_parecer'
                               ,'$this->ed73_c_aprovmin'
                               ,'$this->ed73_c_amparo'
                               ,$this->ed73_valorreal
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Resultados do Diário de Classe ($this->ed73_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Resultados do Diário de Classe já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Resultados do Diário de Classe ($this->ed73_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed73_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed73_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008672,'$this->ed73_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010120,1008672,'','".AddSlashes(pg_result($resaco,0,'ed73_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010120,1008673,'','".AddSlashes(pg_result($resaco,0,'ed73_i_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010120,1008674,'','".AddSlashes(pg_result($resaco,0,'ed73_i_procresultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010120,1008675,'','".AddSlashes(pg_result($resaco,0,'ed73_i_valornota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010120,1008676,'','".AddSlashes(pg_result($resaco,0,'ed73_c_valorconceito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010120,1008677,'','".AddSlashes(pg_result($resaco,0,'ed73_t_parecer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010120,1008678,'','".AddSlashes(pg_result($resaco,0,'ed73_c_aprovmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010120,1009249,'','".AddSlashes(pg_result($resaco,0,'ed73_c_amparo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010120,21939,'','".AddSlashes(pg_result($resaco,0,'ed73_valorreal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed73_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update diarioresultado set ";
     $virgula = "";
     if(trim($this->ed73_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed73_i_codigo"])){
       $sql  .= $virgula." ed73_i_codigo = $this->ed73_i_codigo ";
       $virgula = ",";
       if(trim($this->ed73_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed73_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed73_i_diario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed73_i_diario"])){
       $sql  .= $virgula." ed73_i_diario = $this->ed73_i_diario ";
       $virgula = ",";
       if(trim($this->ed73_i_diario) == null ){
         $this->erro_sql = " Campo Diário de Classe não informado.";
         $this->erro_campo = "ed73_i_diario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed73_i_procresultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed73_i_procresultado"])){
       $sql  .= $virgula." ed73_i_procresultado = $this->ed73_i_procresultado ";
       $virgula = ",";
       if(trim($this->ed73_i_procresultado) == null ){
         $this->erro_sql = " Campo Resultado não informado.";
         $this->erro_campo = "ed73_i_procresultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed73_i_valornota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed73_i_valornota"])){
        if(trim($this->ed73_i_valornota)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed73_i_valornota"])){
           $this->ed73_i_valornota = "null" ;
        }
       $sql  .= $virgula." ed73_i_valornota = $this->ed73_i_valornota ";
       $virgula = ",";
     }
     if(trim($this->ed73_c_valorconceito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed73_c_valorconceito"])){
       $sql  .= $virgula." ed73_c_valorconceito = '$this->ed73_c_valorconceito' ";
       $virgula = ",";
     }
     if(trim($this->ed73_t_parecer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed73_t_parecer"])){
       $sql  .= $virgula." ed73_t_parecer = '$this->ed73_t_parecer' ";
       $virgula = ",";
     }
     if(trim($this->ed73_c_aprovmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed73_c_aprovmin"])){
       $sql  .= $virgula." ed73_c_aprovmin = '$this->ed73_c_aprovmin' ";
       $virgula = ",";
       if(trim($this->ed73_c_aprovmin) == null ){
         $this->erro_sql = " Campo Aproveitamento Mínimo não informado.";
         $this->erro_campo = "ed73_c_aprovmin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed73_c_amparo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed73_c_amparo"])){
       $sql  .= $virgula." ed73_c_amparo = '$this->ed73_c_amparo' ";
       $virgula = ",";
     }

     if(trim($this->ed73_valorreal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed73_valorreal"])){
        if(trim($this->ed73_valorreal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed73_valorreal"])){
           $this->ed73_valorreal = "null" ;
        }
       $sql  .= $virgula." ed73_valorreal = $this->ed73_valorreal ";
       $virgula = ",";
     }

     $sql .= " where ";
     if($ed73_i_codigo!=null){
       $sql .= " ed73_i_codigo = $this->ed73_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed73_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008672,'$this->ed73_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed73_i_codigo"]) || $this->ed73_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010120,1008672,'".AddSlashes(pg_result($resaco,$conresaco,'ed73_i_codigo'))."','$this->ed73_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed73_i_diario"]) || $this->ed73_i_diario != "")
             $resac = db_query("insert into db_acount values($acount,1010120,1008673,'".AddSlashes(pg_result($resaco,$conresaco,'ed73_i_diario'))."','$this->ed73_i_diario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed73_i_procresultado"]) || $this->ed73_i_procresultado != "")
             $resac = db_query("insert into db_acount values($acount,1010120,1008674,'".AddSlashes(pg_result($resaco,$conresaco,'ed73_i_procresultado'))."','$this->ed73_i_procresultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed73_i_valornota"]) || $this->ed73_i_valornota != "")
             $resac = db_query("insert into db_acount values($acount,1010120,1008675,'".AddSlashes(pg_result($resaco,$conresaco,'ed73_i_valornota'))."','$this->ed73_i_valornota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed73_c_valorconceito"]) || $this->ed73_c_valorconceito != "")
             $resac = db_query("insert into db_acount values($acount,1010120,1008676,'".AddSlashes(pg_result($resaco,$conresaco,'ed73_c_valorconceito'))."','$this->ed73_c_valorconceito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed73_t_parecer"]) || $this->ed73_t_parecer != "")
             $resac = db_query("insert into db_acount values($acount,1010120,1008677,'".AddSlashes(pg_result($resaco,$conresaco,'ed73_t_parecer'))."','$this->ed73_t_parecer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed73_c_aprovmin"]) || $this->ed73_c_aprovmin != "")
             $resac = db_query("insert into db_acount values($acount,1010120,1008678,'".AddSlashes(pg_result($resaco,$conresaco,'ed73_c_aprovmin'))."','$this->ed73_c_aprovmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed73_c_amparo"]) || $this->ed73_c_amparo != "")
             $resac = db_query("insert into db_acount values($acount,1010120,1009249,'".AddSlashes(pg_result($resaco,$conresaco,'ed73_c_amparo'))."','$this->ed73_c_amparo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed73_valorreal"]) || $this->ed73_valorreal != "")
             $resac = db_query("insert into db_acount values($acount,1010120,21939,'".AddSlashes(pg_result($resaco,$conresaco,'ed73_valorreal'))."','$this->ed73_valorreal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultados do Diário de Classe não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed73_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Resultados do Diário de Classe não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed73_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed73_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed73_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed73_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008672,'$ed73_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010120,1008672,'','".AddSlashes(pg_result($resaco,$iresaco,'ed73_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010120,1008673,'','".AddSlashes(pg_result($resaco,$iresaco,'ed73_i_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010120,1008674,'','".AddSlashes(pg_result($resaco,$iresaco,'ed73_i_procresultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010120,1008675,'','".AddSlashes(pg_result($resaco,$iresaco,'ed73_i_valornota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010120,1008676,'','".AddSlashes(pg_result($resaco,$iresaco,'ed73_c_valorconceito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010120,1008677,'','".AddSlashes(pg_result($resaco,$iresaco,'ed73_t_parecer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010120,1008678,'','".AddSlashes(pg_result($resaco,$iresaco,'ed73_c_aprovmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010120,1009249,'','".AddSlashes(pg_result($resaco,$iresaco,'ed73_c_amparo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010120,21939,'','".AddSlashes(pg_result($resaco,$iresaco,'ed73_valorreal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from diarioresultado
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed73_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed73_i_codigo = $ed73_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultados do Diário de Classe não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed73_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Resultados do Diário de Classe não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed73_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed73_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:diarioresultado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed73_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from diarioresultado ";
     $sql .= "      inner join procresultado  on  procresultado.ed43_i_codigo = diarioresultado.ed73_i_procresultado";
     $sql .= "      inner join diario  on  diario.ed95_i_codigo = diarioresultado.ed73_i_diario";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procresultado.ed43_i_formaavaliacao";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = procresultado.ed43_i_procedimento";
     $sql .= "      inner join resultado  on  resultado.ed42_i_codigo = procresultado.ed43_i_resultado";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = diario.ed95_i_escola";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = diario.ed95_i_regencia";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = diario.ed95_i_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diario.ed95_i_aluno";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = diario.ed95_i_calendario";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed73_i_codigo)) {
         $sql2 .= " where diarioresultado.ed73_i_codigo = $ed73_i_codigo ";
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
   public function sql_query_file ($ed73_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from diarioresultado ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed73_i_codigo)){
         $sql2 .= " where diarioresultado.ed73_i_codigo = $ed73_i_codigo ";
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

   function sql_query_diarioavalres($iCodigo = null, $sCampos = '*', $sOrdem = null,
                                   $sWhereDiarioAval = '', $sWhereDiarioRes = '', $sWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= ' from ';
    $sSql .= '((select diarioavaliacao.ed72_i_codigo as codigo, periodoavaliacao.ed09_c_abrev as abrev, ';
    $sSql .= '         diarioavaliacao.ed72_c_amparo as amparo, procavaliacao.ed41_i_procavalvinc as avalvinc, ';
    $sSql .= '         procavaliacao.ed41_i_procresultvinc as resultvinc, avalcompoeres.ed44_i_peso as peso, ';
    $sSql .= '         diarioavaliacao.ed72_c_valorconceito as conceito, diarioavaliacao.ed72_i_valornota as nota, ';
    $sSql .= '         procavaliacao.ed41_i_sequencia as sequencia, avalcompoeres.ed44_i_codigo as codcomp, ';
    $sSql .= '         diarioavaliacao.ed72_i_diario as diario, ';
    $sSql .= '         formaavaliacao.*, 1 as tipo ';
    $sSql .= '    from diarioavaliacao ';
    $sSql .= '  inner join procavaliacao on procavaliacao.ed41_i_codigo = diarioavaliacao.ed72_i_procavaliacao ';
    $sSql .= '  inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao ';
    $sSql .= '  inner join avalcompoeres on avalcompoeres.ed44_i_procavaliacao = procavaliacao.ed41_i_codigo ';
    $sSql .= '  inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao ';
    if (!empty($sWhereDiarioAval)) {
      $sSql .= ' where '.$sWhereDiarioAval;
    }
    $sSql .= ' )';
    $sSql .= '     union ';
    $sSql .= ' (select diarioresultado.ed73_i_codigo as codigo, resultado.ed42_c_abrev as abrev, ';
    $sSql .= '         diarioresultado.ed73_c_amparo as amparo, 0 as avalvinc, ';
    $sSql .= '         0 as resultvinc, rescompoeres.ed68_i_peso as peso, ';
    $sSql .= '         diarioresultado.ed73_c_valorconceito as conceito, diarioresultado.ed73_i_valornota as nota, ';
    $sSql .= '         procresultado.ed43_i_sequencia as sequencia, rescompoeres.ed68_i_codigo as codcomp, ';
    $sSql .= '         diarioresultado.ed73_i_diario as diario, ';
    $sSql .= '         formaavaliacao.*, 2 as tipo ';
    $sSql .= '    from diarioresultado ';
    $sSql .= '      inner join procresultado on procresultado.ed43_i_codigo = diarioresultado.ed73_i_procresultado ';
    $sSql .= '      inner join resultado on resultado.ed42_i_codigo = procresultado.ed43_i_resultado ';
    $sSql .= '      inner join rescompoeres on rescompoeres.ed68_i_procresultcomp = procresultado.ed43_i_codigo ';
    $sSql .= '      inner join formaavaliacao on formaavaliacao.ed37_i_codigo = procresultado.ed43_i_formaavaliacao ';
    if (!empty($sWhereDiarioRes)) {
      $sSql .= ' where '.$sWhereDiarioRes;
    }
    $sSql .= ' )) as procavalres ';

    if (!empty($sWhere)) {
      $sSql .= "where $sWhere";
    }

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

  public function sql_query_resultado_outras_disciplina_aluno ($ed73_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

    $sSql = 'select ';
    if ($campos != '*') {

      $sCamposSql = split('#', $campos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

}

    } else {
      $sSql .= $campos;
    }

    $sSql .= " from diarioresultado ";
    $sSql .= "      inner join procresultado  on  procresultado.ed43_i_codigo = diarioresultado.ed73_i_procresultado";
    $sSql .= "      inner join diario  on  diario.ed95_i_codigo = diarioresultado.ed73_i_diario";

    $sql2 = "";
    if($dbwhere==""){
      if($ed73_i_codigo!=null ){
        $sql2 .= " where diarioresultado.ed73_i_codigo = $ed73_i_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sSql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sSql;
  }

}
?>