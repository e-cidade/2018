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

//MODULO: educação
//CLASSE DA ENTIDADE diarioavaliacao
class cl_diarioavaliacao {
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
   var $ed72_i_codigo = 0;
   var $ed72_i_diario = 0;
   var $ed72_i_procavaliacao = 0;
   var $ed72_i_numfaltas = 0;
   var $ed72_i_valornota = "";
   var $ed72_c_valorconceito = null;
   var $ed72_t_parecer = null;
   var $ed72_c_aprovmin = null;
   var $ed72_c_amparo = null;
   var $ed72_t_obs = null;
   var $ed72_i_escola = 0;
   var $ed72_c_tipo = null;
   var $ed72_c_convertido = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed72_i_codigo = int8 = Código
                 ed72_i_diario = int8 = Diário de Classe
                 ed72_i_procavaliacao = int8 = Período de Avaliação
                 ed72_i_numfaltas = int4 = Faltas
                 ed72_i_valornota = float8 = Nota
                 ed72_c_valorconceito = char(2) = Conceito
                 ed72_t_parecer = text = Parecer
                 ed72_c_aprovmin = char(1) = Aproveitamento Mínimo
                 ed72_c_amparo = char(1) = Amparo
                 ed72_t_obs = text = Observações
                 ed72_i_escola = int8 = Escola de Origem do Aproveitamento
                 ed72_c_tipo = char(1) = Tipo de Escola
                 ed72_c_convertido = char(1)) = Nota Convertida
                 ";
   //funcao construtor da classe
   function cl_diarioavaliacao() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diarioavaliacao");
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
       $this->ed72_i_codigo = ($this->ed72_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_i_codigo"]:$this->ed72_i_codigo);
       $this->ed72_i_diario = ($this->ed72_i_diario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_i_diario"]:$this->ed72_i_diario);
       $this->ed72_i_procavaliacao = ($this->ed72_i_procavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_i_procavaliacao"]:$this->ed72_i_procavaliacao);
       $this->ed72_i_numfaltas = ($this->ed72_i_numfaltas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_i_numfaltas"]:$this->ed72_i_numfaltas);
       $this->ed72_i_valornota = ($this->ed72_i_valornota == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_i_valornota"]:$this->ed72_i_valornota);
       $this->ed72_c_valorconceito = ($this->ed72_c_valorconceito == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_c_valorconceito"]:$this->ed72_c_valorconceito);
       $this->ed72_t_parecer = ($this->ed72_t_parecer == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_t_parecer"]:$this->ed72_t_parecer);
       $this->ed72_c_aprovmin = ($this->ed72_c_aprovmin == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_c_aprovmin"]:$this->ed72_c_aprovmin);
       $this->ed72_c_amparo = ($this->ed72_c_amparo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_c_amparo"]:$this->ed72_c_amparo);
       $this->ed72_t_obs = ($this->ed72_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_t_obs"]:$this->ed72_t_obs);
       $this->ed72_i_escola = ($this->ed72_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_i_escola"]:$this->ed72_i_escola);
       $this->ed72_c_tipo = ($this->ed72_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_c_tipo"]:$this->ed72_c_tipo);
       $this->ed72_c_convertido = ($this->ed72_c_convertido == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_c_convertido"]:$this->ed72_c_convertido);
     }else{
       $this->ed72_i_codigo = ($this->ed72_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed72_i_codigo"]:$this->ed72_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed72_i_codigo){
      $this->atualizacampos();
     if($this->ed72_i_diario == null ){
       $this->erro_sql = " Campo Diário de Classe nao Informado.";
       $this->erro_campo = "ed72_i_diario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed72_i_procavaliacao == null ){
       $this->erro_sql = " Campo Período de Avaliação nao Informado.";
       $this->erro_campo = "ed72_i_procavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed72_i_numfaltas == null ){
       $this->ed72_i_numfaltas = "null";
     }
     if($this->ed72_i_valornota == null ){
       $this->ed72_i_valornota = "null";
     }
     if($this->ed72_c_aprovmin == null ){
       $this->erro_sql = " Campo Aproveitamento Mínimo nao Informado.";
       $this->erro_campo = "ed72_c_aprovmin";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed72_c_amparo == null ){
       $this->erro_sql = " Campo Amparo nao Informado.";
       $this->erro_campo = "ed72_c_amparo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed72_i_escola == null ){
       $this->erro_sql = " Campo Escola de Origem do Aproveitamento nao Informado.";
       $this->erro_campo = "ed72_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed72_c_tipo == null ){
       $this->erro_sql = " Campo Tipo de Escola nao Informado.";
       $this->erro_campo = "ed72_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed72_c_convertido == null ){
       $this->erro_sql = " Campo Nota Convertida nao Informado.";
       $this->erro_campo = "ed72_c_convertido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed72_i_codigo == "" || $ed72_i_codigo == null ){
       $result = db_query("select nextval('diarioavaliacao_ed72_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diarioavaliacao_ed72_i_codigo_seq do campo: ed72_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed72_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from diarioavaliacao_ed72_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed72_i_codigo)){
         $this->erro_sql = " Campo ed72_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed72_i_codigo = $ed72_i_codigo;
       }
     }
     if(($this->ed72_i_codigo == null) || ($this->ed72_i_codigo == "") ){
       $this->erro_sql = " Campo ed72_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diarioavaliacao(
                                       ed72_i_codigo
                                      ,ed72_i_diario
                                      ,ed72_i_procavaliacao
                                      ,ed72_i_numfaltas
                                      ,ed72_i_valornota
                                      ,ed72_c_valorconceito
                                      ,ed72_t_parecer
                                      ,ed72_c_aprovmin
                                      ,ed72_c_amparo
                                      ,ed72_t_obs
                                      ,ed72_i_escola
                                      ,ed72_c_tipo
                                      ,ed72_c_convertido
                       )
                values (
                                $this->ed72_i_codigo
                               ,$this->ed72_i_diario
                               ,$this->ed72_i_procavaliacao
                               ,$this->ed72_i_numfaltas
                               ,$this->ed72_i_valornota
                               ,'$this->ed72_c_valorconceito'
                               ,'$this->ed72_t_parecer'
                               ,'$this->ed72_c_aprovmin'
                               ,'$this->ed72_c_amparo'
                               ,'$this->ed72_t_obs'
                               ,$this->ed72_i_escola
                               ,'$this->ed72_c_tipo'
                               ,'$this->ed72_c_convertido'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliações do Diário de Classe ($this->ed72_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliações do Diário de Classe já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliações do Diário de Classe ($this->ed72_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed72_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed72_i_codigo));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008663,'$this->ed72_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010119,1008663,'','".AddSlashes(pg_result($resaco,0,'ed72_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010119,1008664,'','".AddSlashes(pg_result($resaco,0,'ed72_i_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010119,1008665,'','".AddSlashes(pg_result($resaco,0,'ed72_i_procavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010119,1008666,'','".AddSlashes(pg_result($resaco,0,'ed72_i_numfaltas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010119,1008667,'','".AddSlashes(pg_result($resaco,0,'ed72_i_valornota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010119,1008668,'','".AddSlashes(pg_result($resaco,0,'ed72_c_valorconceito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010119,1008669,'','".AddSlashes(pg_result($resaco,0,'ed72_t_parecer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010119,1008670,'','".AddSlashes(pg_result($resaco,0,'ed72_c_aprovmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010119,1008671,'','".AddSlashes(pg_result($resaco,0,'ed72_c_amparo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010119,1009243,'','".AddSlashes(pg_result($resaco,0,'ed72_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010119,12153,'','".AddSlashes(pg_result($resaco,0,'ed72_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010119,12154,'','".AddSlashes(pg_result($resaco,0,'ed72_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed72_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update diarioavaliacao set ";
     $virgula = "";
     if(trim($this->ed72_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed72_i_codigo"])){
       $sql  .= $virgula." ed72_i_codigo = $this->ed72_i_codigo ";
       $virgula = ",";
       if(trim($this->ed72_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed72_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed72_i_diario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed72_i_diario"])){
       $sql  .= $virgula." ed72_i_diario = $this->ed72_i_diario ";
       $virgula = ",";
       if(trim($this->ed72_i_diario) == null ){
         $this->erro_sql = " Campo Diário de Classe nao Informado.";
         $this->erro_campo = "ed72_i_diario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed72_i_procavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed72_i_procavaliacao"])){
       $sql  .= $virgula." ed72_i_procavaliacao = $this->ed72_i_procavaliacao ";
       $virgula = ",";
       if(trim($this->ed72_i_procavaliacao) == null ){
         $this->erro_sql = " Campo Período de Avaliação nao Informado.";
         $this->erro_campo = "ed72_i_procavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed72_i_numfaltas)==null){
      $this->ed72_i_numfaltas = "null" ;
     }
     $sql  .= $virgula." ed72_i_numfaltas = $this->ed72_i_numfaltas ";
     $virgula = ",";

     if(trim($this->ed72_i_valornota)==null){
        $this->ed72_i_valornota = "null" ;
     }
     $sql  .= $virgula." ed72_i_valornota = $this->ed72_i_valornota ";
     $virgula = ",";

     if(trim($this->ed72_c_valorconceito)==null){
        $this->ed72_c_valorconceito = "" ;
     }
     $sql  .= $virgula." ed72_c_valorconceito = '$this->ed72_c_valorconceito' ";
     $virgula = ",";

     if (trim($this->ed72_t_parecer) == null) {
       $this->ed72_t_parecer = '';
     }
     $sql     .= $virgula." ed72_t_parecer = '$this->ed72_t_parecer' ";
     $virgula  = ",";
     if(trim($this->ed72_c_aprovmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed72_c_aprovmin"])){
       $sql  .= $virgula." ed72_c_aprovmin = '$this->ed72_c_aprovmin' ";
       $virgula = ",";
       if(trim($this->ed72_c_aprovmin) == null ){
         $this->erro_sql = " Campo Aproveitamento Mínimo nao Informado.";
         $this->erro_campo = "ed72_c_aprovmin";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed72_c_amparo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed72_c_amparo"])){
       $sql  .= $virgula." ed72_c_amparo = '$this->ed72_c_amparo' ";
       $virgula = ",";
       if(trim($this->ed72_c_amparo) == null ){
         $this->erro_sql = " Campo Amparo nao Informado.";
         $this->erro_campo = "ed72_c_amparo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed72_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed72_i_escola"])){
       $sql  .= $virgula." ed72_i_escola = $this->ed72_i_escola ";
       $virgula = ",";
       if(trim($this->ed72_i_escola) == null ){
         $this->erro_sql = " Campo Escola de Origem do Aproveitamento nao Informado.";
         $this->erro_campo = "ed72_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed72_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed72_c_tipo"])){
       $sql  .= $virgula." ed72_c_tipo = '$this->ed72_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed72_c_tipo) == null ){
         $this->erro_sql = " Campo Tipo de Escola nao Informado.";
         $this->erro_campo = "ed72_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed72_c_convertido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed72_c_convertido"])){
       $sql  .= $virgula." ed72_c_convertido = '$this->ed72_c_convertido' ";
       $virgula = ",";
       if(trim($this->ed72_c_convertido) == null ){
         $this->erro_sql = " Campo Nota Convertida nao Informado.";
         $this->erro_campo = "ed72_c_convertido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if( trim( $this->ed72_t_obs ) == null ) {
       $this->ed72_t_obs = "" ;
     }

     $sql     .= $virgula." ed72_t_obs = '$this->ed72_t_obs' ";
     $virgula  = ",";

     $sql .= " where ";
     if($ed72_i_codigo!=null){
       $sql .= " ed72_i_codigo = $this->ed72_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed72_i_codigo));
     if($this->numrows>0){

       $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
       if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008663,'$this->ed72_i_codigo','A')");
             $resac = db_query("insert into db_acount values($acount,1010119,1008663,'".AddSlashes(pg_result($resaco,$conresaco,'ed72_i_codigo'))."','$this->ed72_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             $resac = db_query("insert into db_acount values($acount,1010119,1008664,'".AddSlashes(pg_result($resaco,$conresaco,'ed72_i_diario'))."','$this->ed72_i_diario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             $resac = db_query("insert into db_acount values($acount,1010119,1008665,'".AddSlashes(pg_result($resaco,$conresaco,'ed72_i_procavaliacao'))."','$this->ed72_i_procavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             $resac = db_query("insert into db_acount values($acount,1010119,1008666,'".AddSlashes(pg_result($resaco,$conresaco,'ed72_i_numfaltas'))."','$this->ed72_i_numfaltas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             $resac = db_query("insert into db_acount values($acount,1010119,1008667,'".AddSlashes(pg_result($resaco,$conresaco,'ed72_i_valornota'))."','$this->ed72_i_valornota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             $resac = db_query("insert into db_acount values($acount,1010119,1008668,'".AddSlashes(pg_result($resaco,$conresaco,'ed72_c_valorconceito'))."','$this->ed72_c_valorconceito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             $resac = db_query("insert into db_acount values($acount,1010119,1008669,'".AddSlashes(pg_result($resaco,$conresaco,'ed72_t_parecer'))."','$this->ed72_t_parecer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             $resac = db_query("insert into db_acount values($acount,1010119,1008670,'".AddSlashes(pg_result($resaco,$conresaco,'ed72_c_aprovmin'))."','$this->ed72_c_aprovmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             $resac = db_query("insert into db_acount values($acount,1010119,1008671,'".AddSlashes(pg_result($resaco,$conresaco,'ed72_c_amparo'))."','$this->ed72_c_amparo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             $resac = db_query("insert into db_acount values($acount,1010119,1009243,'".AddSlashes(pg_result($resaco,$conresaco,'ed72_t_obs'))."','$this->ed72_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             $resac = db_query("insert into db_acount values($acount,1010119,12153,'".AddSlashes(pg_result($resaco,$conresaco,'ed72_i_escola'))."','$this->ed72_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             $resac = db_query("insert into db_acount values($acount,1010119,12154,'".AddSlashes(pg_result($resaco,$conresaco,'ed72_c_tipo'))."','$this->ed72_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }

     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliações do Diário de Classe nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed72_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliações do Diário de Classe nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed72_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed72_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed72_i_codigo=null,$dbwhere=null) {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($ed72_i_codigo));
       }else{
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008663,'$ed72_i_codigo','E')");
           $resac = db_query("insert into db_acount values($acount,1010119,1008663,'','".AddSlashes(pg_result($resaco,$iresaco,'ed72_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010119,1008664,'','".AddSlashes(pg_result($resaco,$iresaco,'ed72_i_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010119,1008665,'','".AddSlashes(pg_result($resaco,$iresaco,'ed72_i_procavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010119,1008666,'','".AddSlashes(pg_result($resaco,$iresaco,'ed72_i_numfaltas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010119,1008667,'','".AddSlashes(pg_result($resaco,$iresaco,'ed72_i_valornota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010119,1008668,'','".AddSlashes(pg_result($resaco,$iresaco,'ed72_c_valorconceito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010119,1008669,'','".AddSlashes(pg_result($resaco,$iresaco,'ed72_t_parecer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010119,1008670,'','".AddSlashes(pg_result($resaco,$iresaco,'ed72_c_aprovmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010119,1008671,'','".AddSlashes(pg_result($resaco,$iresaco,'ed72_c_amparo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010119,1009243,'','".AddSlashes(pg_result($resaco,$iresaco,'ed72_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010119,12153,'','".AddSlashes(pg_result($resaco,$iresaco,'ed72_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010119,12154,'','".AddSlashes(pg_result($resaco,$iresaco,'ed72_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from diarioavaliacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed72_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed72_i_codigo = $ed72_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliações do Diário de Classe nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed72_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliações do Diário de Classe nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed72_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed72_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:diarioavaliacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed72_i_codigo=null,$campos="*",$ordem=null,$dbwhere="", $group_by = ''){
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
     $sql .= " from diarioavaliacao ";
     $sql .= "      inner join procavaliacao  on  procavaliacao.ed41_i_codigo = diarioavaliacao.ed72_i_procavaliacao";
     $sql .= "      inner join diario  on  diario.ed95_i_codigo = diarioavaliacao.ed72_i_diario";
     $sql .= "      left join amparo  on  amparo.ed81_i_diario = diario.ed95_i_codigo";
     $sql .= "      left join justificativa  on  justificativa.ed06_i_codigo = amparo.ed81_i_justificativa";
     $sql .= "      left join convencaoamp  on  convencaoamp.ed250_i_codigo = amparo.ed81_i_convencaoamp";
     $sql .= "      inner join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = procavaliacao.ed41_i_procedimento";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = diario.ed95_i_escola";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = diario.ed95_i_regencia";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = diario.ed95_i_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diario.ed95_i_aluno";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = diario.ed95_i_calendario";
     $sql .= "      left join escola as escolaorigem  on  escolaorigem.ed18_i_codigo = diarioavaliacao.ed72_i_escola";
     $sql .= "      left join censouf  on  censouf.ed260_i_codigo = escolaorigem.ed18_i_censouf";
     $sql .= "      left join censomunic  on  censomunic.ed261_i_codigo = escolaorigem.ed18_i_censomunic";
     $sql .= "      left join escolaproc on  escolaproc.ed82_i_codigo = diarioavaliacao.ed72_i_escola";
     $sql2 = "";


     if($dbwhere==""){
       if($ed72_i_codigo!=null ){
         $sql2 .= " where diarioavaliacao.ed72_i_codigo = $ed72_i_codigo ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     if( $group_by != null ){
        $sql2 .= " group by $group_by ";
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

function sql_query_guia ( $ed72_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from diarioavaliacao ";
     $sql .= "      inner join procavaliacao  on  procavaliacao.ed41_i_codigo = diarioavaliacao.ed72_i_procavaliacao";
     $sql .= "      inner join diario  on  diario.ed95_i_codigo = diarioavaliacao.ed72_i_diario";
     $sql .= "      left join diarioresultado  on  diarioresultado.ed73_i_diario = diario.ed95_i_codigo";
     $sql .= "      left join procresultado  on  procresultado.ed43_i_codigo = diarioresultado.ed73_i_procresultado";
     $sql .= "                              and  procresultado.ed43_c_geraresultado = 'S'";
     $sql .= "      left join amparo  on  amparo.ed81_i_diario = diario.ed95_i_codigo";
     $sql .= "      left join justificativa  on  justificativa.ed06_i_codigo = amparo.ed81_i_justificativa";
     $sql .= "      left join convencaoamp  on  convencaoamp.ed250_i_codigo = amparo.ed81_i_convencaoamp";
     $sql .= "      inner join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = procavaliacao.ed41_i_procedimento";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = diario.ed95_i_escola";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = diario.ed95_i_regencia";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = diario.ed95_i_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diario.ed95_i_aluno";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = diario.ed95_i_calendario";
     $sql .= "      inner join matricula  on  matricula.ed60_i_aluno = aluno.ed47_i_codigo and matricula.ed60_i_turma=regencia.ed59_i_turma";
     $sql2 = "";
     if($dbwhere==""){
       if($ed72_i_codigo!=null ){
         $sql2 .= " where diarioavaliacao.ed72_i_codigo = $ed72_i_codigo ";
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
   function sql_query_file ( $ed72_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from diarioavaliacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed72_i_codigo!=null ){
         $sql2 .= " where diarioavaliacao.ed72_i_codigo = $ed72_i_codigo ";
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
  function sql_query_apagargeral($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from diarioavaliacao ";
    $sSql .= "      inner join diario  on  diario.ed95_i_codigo = diarioavaliacao.ed72_i_diario";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where diarioavaliacao.ed72_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

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

function sql_query_faltas($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

    $sSql      .= "       inner join procavaliacao on ed41_i_codigo = ed72_i_procavaliacao ";
    $sSql      .= "       inner join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao ";
    $sSql      .= "       inner join avalfreqres on ed67_i_procavaliacao = ed41_i_codigo ";
    $sSql      .= "       inner join diario on ed95_i_codigo = ed72_i_diario ";
    $sSql      .= "       inner join regencia on ed59_i_codigo = ed95_i_regencia ";
    $sSql      .= "       inner join regenciaperiodo on ed78_i_procavaliacao = ed41_i_codigo ";
    $sSql      .= "                                  and ed78_i_regencia = ed95_i_regencia  ";
    $sSql      .= "       left join abonofalta on ed80_i_diarioavaliacao = ed72_i_codigo ";;
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where diarioavaliacao.ed72_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

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

  function sql_query_diario($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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
    $sSql .= " from diarioavaliacao ";
    $sSql .= "      inner join diario  on  diario.ed95_i_codigo = diarioavaliacao.ed72_i_diario";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where diarioavaliacao.ed72_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

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
}
?>