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
//CLASSE DA ENTIDADE basemps
class cl_basemps {
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
   var $ed34_i_codigo = 0;
   var $ed34_i_base = 0;
   var $ed34_i_serie = 0;
   var $ed34_i_disciplina = 0;
   var $ed34_i_qtdperiodo = 0;
   var $ed34_i_chtotal = 0;
   var $ed34_c_condicao = null;
   var $ed34_i_ordenacao = 0;
   var $ed34_lancarhistorico = 't';
   var $ed34_caracterreprobatorio = 't';
   var $ed34_disiciplinaglobalizada = 'f';
   var $ed34_basecomum = 't';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed34_i_codigo = int8 = Base
                 ed34_i_base = int8 = Base
                 ed34_i_serie = int8 = Série/Ano
                 ed34_i_disciplina = int8 = Disciplina
                 ed34_i_qtdperiodo = int4 = Quantidade de Horas - Aula
                 ed34_i_chtotal = int4 = Carga Horária Total
                 ed34_c_condicao = char(2) = Matrícula
                 ed34_i_ordenacao = int4 = Ordenar Disciplinas
                 ed34_lancarhistorico = bool = Lançar no Histórico
                 ed34_caracterreprobatorio = bool = Carácter Reprobatório 
                 ed34_disiciplinaglobalizada = bool = Disciplina Globalizada 
                 ed34_basecomum = bool = Base Comum 
                 ";
   //funcao construtor da classe
   function cl_basemps() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("basemps");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]."?ed34_i_base=".@$GLOBALS["HTTP_POST_VARS"]["ed34_i_base"]."&ed31_c_descr=".@$GLOBALS["HTTP_POST_VARS"]["ed31_c_descr"]."&ed34_i_serie=".@$GLOBALS["HTTP_POST_VARS"]["ed34_i_serie"]."&ed11_c_descr=".@$GLOBALS["HTTP_POST_VARS"]["ed11_c_descr"]."&curso=".@$GLOBALS["HTTP_POST_VARS"]["curso"]."&discglob=".@$GLOBALS["HTTP_POST_VARS"]["discglob"]."&qtdper=".@$GLOBALS["HTTP_POST_VARS"]["qtdper"]);
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
       $this->ed34_i_codigo = ($this->ed34_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed34_i_codigo"]:$this->ed34_i_codigo);
       $this->ed34_i_base = ($this->ed34_i_base == ""?@$GLOBALS["HTTP_POST_VARS"]["ed34_i_base"]:$this->ed34_i_base);
       $this->ed34_i_serie = ($this->ed34_i_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed34_i_serie"]:$this->ed34_i_serie);
       $this->ed34_i_disciplina = ($this->ed34_i_disciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed34_i_disciplina"]:$this->ed34_i_disciplina);
       $this->ed34_i_qtdperiodo = ($this->ed34_i_qtdperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed34_i_qtdperiodo"]:$this->ed34_i_qtdperiodo);
       $this->ed34_i_chtotal = ($this->ed34_i_chtotal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed34_i_chtotal"]:$this->ed34_i_chtotal);
       $this->ed34_c_condicao = ($this->ed34_c_condicao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed34_c_condicao"]:$this->ed34_c_condicao);
       $this->ed34_i_ordenacao = ($this->ed34_i_ordenacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed34_i_ordenacao"]:$this->ed34_i_ordenacao);
       $this->ed34_lancarhistorico = ($this->ed34_lancarhistorico == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed34_lancarhistorico"]:$this->ed34_lancarhistorico);
       $this->ed34_caracterreprobatorio = ($this->ed34_caracterreprobatorio == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed34_caracterreprobatorio"]:$this->ed34_caracterreprobatorio);
       $this->ed34_disiciplinaglobalizada = ($this->ed34_disiciplinaglobalizada == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed34_disiciplinaglobalizada"]:$this->ed34_disiciplinaglobalizada);
       $this->ed34_basecomum = ($this->ed34_basecomum == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed34_basecomum"]:$this->ed34_basecomum);
     }else{
       $this->ed34_i_codigo = ($this->ed34_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed34_i_codigo"]:$this->ed34_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed34_i_codigo){
      $this->atualizacampos();
     if($this->ed34_i_base == null ){
       $this->erro_sql = " Campo Base não informado.";
       $this->erro_campo = "ed34_i_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed34_i_serie == null ){
       $this->erro_sql = " Campo Série/Ano nao Informado.";
       $this->erro_campo = "ed34_i_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed34_i_disciplina == null ){
       $this->erro_sql = " Campo Disciplina não informado.";
       $this->erro_campo = "ed34_i_disciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed34_i_qtdperiodo == null ){
       $this->erro_sql = " Campo Quantidade de Horas - Aula não informado.";
       $this->erro_campo = "ed34_i_qtdperiodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed34_i_chtotal == null ){
       $this->ed34_i_chtotal = "0";
     }
     if($this->ed34_c_condicao == null ){
       $this->erro_sql = " Campo Matrícula não informado.";
       $this->erro_campo = "ed34_c_condicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed34_i_ordenacao == null ){
       $this->ed34_i_ordenacao = "0";
     }
     if($this->ed34_lancarhistorico == null ){
       $this->erro_sql = " Campo Lançar no Histórico não informado.";
       $this->erro_campo = "ed34_lancarhistorico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed34_caracterreprobatorio == null ){ 
       $this->erro_sql = " Campo Carácter Reprobatório não informado.";
       $this->erro_campo = "ed34_caracterreprobatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed34_disiciplinaglobalizada == null ){ 
       $this->erro_sql = " Campo Disciplina Globalizada não informado.";
       $this->erro_campo = "ed34_disiciplinaglobalizada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed34_basecomum == null ){ 
       $this->erro_sql = " Campo Base Comum não informado.";
       $this->erro_campo = "ed34_basecomum";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed34_i_codigo == "" || $ed34_i_codigo == null ){
       $result = db_query("select nextval('basemps_ed34_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: basemps_ed34_i_codigo_seq do campo: ed34_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed34_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from basemps_ed34_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed34_i_codigo)){
         $this->erro_sql = " Campo ed34_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed34_i_codigo = $ed34_i_codigo;
       }
     }
     if(($this->ed34_i_codigo == null) || ($this->ed34_i_codigo == "") ){
       $this->erro_sql = " Campo ed34_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into basemps(
                                       ed34_i_codigo
                                      ,ed34_i_base
                                      ,ed34_i_serie
                                      ,ed34_i_disciplina
                                      ,ed34_i_qtdperiodo
                                      ,ed34_i_chtotal
                                      ,ed34_c_condicao
                                      ,ed34_i_ordenacao
                                      ,ed34_lancarhistorico
                                      ,ed34_caracterreprobatorio 
                                      ,ed34_disiciplinaglobalizada 
                                      ,ed34_basecomum 
                       )
                values (
                                $this->ed34_i_codigo
                               ,$this->ed34_i_base
                               ,$this->ed34_i_serie
                               ,$this->ed34_i_disciplina
                               ,$this->ed34_i_qtdperiodo
                               ,$this->ed34_i_chtotal
                               ,'$this->ed34_c_condicao'
                               ,$this->ed34_i_ordenacao
                               ,'$this->ed34_lancarhistorico'
                               ,'$this->ed34_caracterreprobatorio' 
                               ,'$this->ed34_disiciplinaglobalizada' 
                               ,'$this->ed34_basecomum' 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Disciplinas da Base Curricular por Série ($this->ed34_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Disciplinas da Base Curricular por Série já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Disciplinas da Base Curricular por Série ($this->ed34_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed34_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed34_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008368,'$this->ed34_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010061,1008368,'','".AddSlashes(pg_result($resaco,0,'ed34_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010061,1008393,'','".AddSlashes(pg_result($resaco,0,'ed34_i_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010061,1008369,'','".AddSlashes(pg_result($resaco,0,'ed34_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010061,1008370,'','".AddSlashes(pg_result($resaco,0,'ed34_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010061,1008372,'','".AddSlashes(pg_result($resaco,0,'ed34_i_qtdperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010061,1008371,'','".AddSlashes(pg_result($resaco,0,'ed34_i_chtotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010061,1008373,'','".AddSlashes(pg_result($resaco,0,'ed34_c_condicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010061,14691,'','".AddSlashes(pg_result($resaco,0,'ed34_i_ordenacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010061,20320,'','".AddSlashes(pg_result($resaco,0,'ed34_lancarhistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010061,20657,'','".AddSlashes(pg_result($resaco,0,'ed34_caracterreprobatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010061,20659,'','".AddSlashes(pg_result($resaco,0,'ed34_disiciplinaglobalizada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010061,20660,'','".AddSlashes(pg_result($resaco,0,'ed34_basecomum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed34_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update basemps set ";
     $virgula = "";
     if(trim($this->ed34_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_codigo"])){
       $sql  .= $virgula." ed34_i_codigo = $this->ed34_i_codigo ";
       $virgula = ",";
       if(trim($this->ed34_i_codigo) == null ){
         $this->erro_sql = " Campo Base não informado.";
         $this->erro_campo = "ed34_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed34_i_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_base"])){
       $sql  .= $virgula." ed34_i_base = $this->ed34_i_base ";
       $virgula = ",";
       if(trim($this->ed34_i_base) == null ){
         $this->erro_sql = " Campo Base não informado.";
         $this->erro_campo = "ed34_i_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed34_i_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_serie"])){
       $sql  .= $virgula." ed34_i_serie = $this->ed34_i_serie ";
       $virgula = ",";
       if(trim($this->ed34_i_serie) == null ){
         $this->erro_sql = " Campo Série/Ano nao Informado.";
         $this->erro_campo = "ed34_i_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed34_i_disciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_disciplina"])){
       $sql  .= $virgula." ed34_i_disciplina = $this->ed34_i_disciplina ";
       $virgula = ",";
       if(trim($this->ed34_i_disciplina) == null ){
         $this->erro_sql = " Campo Disciplina não informado.";
         $this->erro_campo = "ed34_i_disciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed34_i_qtdperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_qtdperiodo"])){
       $sql  .= $virgula." ed34_i_qtdperiodo = $this->ed34_i_qtdperiodo ";
       $virgula = ",";
       if(trim($this->ed34_i_qtdperiodo) == null ){
         $this->erro_sql = " Campo Quantidade de Horas - Aula não informado.";
         $this->erro_campo = "ed34_i_qtdperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed34_i_chtotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_chtotal"])){
        if(trim($this->ed34_i_chtotal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_chtotal"])){
           $this->ed34_i_chtotal = "0" ;
        }
       $sql  .= $virgula." ed34_i_chtotal = $this->ed34_i_chtotal ";
       $virgula = ",";
     }
     if(trim($this->ed34_c_condicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed34_c_condicao"])){
       $sql  .= $virgula." ed34_c_condicao = '$this->ed34_c_condicao' ";
       $virgula = ",";
       if(trim($this->ed34_c_condicao) == null ){
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "ed34_c_condicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed34_i_ordenacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_ordenacao"])){
        if(trim($this->ed34_i_ordenacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_ordenacao"])){
           $this->ed34_i_ordenacao = "0" ;
        }
       $sql  .= $virgula." ed34_i_ordenacao = $this->ed34_i_ordenacao ";
       $virgula = ",";
     }
     if(trim($this->ed34_lancarhistorico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed34_lancarhistorico"])){
       $sql  .= $virgula." ed34_lancarhistorico = '$this->ed34_lancarhistorico' ";
       $virgula = ",";
       if(trim($this->ed34_lancarhistorico) == null ){
         $this->erro_sql = " Campo Lançar no Histórico não informado.";
         $this->erro_campo = "ed34_lancarhistorico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed34_caracterreprobatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed34_caracterreprobatorio"])){ 
       $sql  .= $virgula." ed34_caracterreprobatorio = '$this->ed34_caracterreprobatorio' ";
       $virgula = ",";
       if(trim($this->ed34_caracterreprobatorio) == null ){ 
         $this->erro_sql = " Campo Carácter Reprobatório não informado.";
         $this->erro_campo = "ed34_caracterreprobatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed34_disiciplinaglobalizada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed34_disiciplinaglobalizada"])){ 
       $sql  .= $virgula." ed34_disiciplinaglobalizada = '$this->ed34_disiciplinaglobalizada' ";
       $virgula = ",";
       if(trim($this->ed34_disiciplinaglobalizada) == null ){ 
         $this->erro_sql = " Campo Disciplina Globalizada não informado.";
         $this->erro_campo = "ed34_disiciplinaglobalizada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed34_basecomum)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed34_basecomum"])){ 
       $sql  .= $virgula." ed34_basecomum = '$this->ed34_basecomum' ";
       $virgula = ",";
       if(trim($this->ed34_basecomum) == null ){ 
         $this->erro_sql = " Campo Base Comum não informado.";
         $this->erro_campo = "ed34_basecomum";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed34_i_codigo!=null){
       $sql .= " ed34_i_codigo = $this->ed34_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed34_i_codigo));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008368,'$this->ed34_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_codigo"]) || $this->ed34_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010061,1008368,'".AddSlashes(pg_result($resaco,$conresaco,'ed34_i_codigo'))."','$this->ed34_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_base"]) || $this->ed34_i_base != "")
             $resac = db_query("insert into db_acount values($acount,1010061,1008393,'".AddSlashes(pg_result($resaco,$conresaco,'ed34_i_base'))."','$this->ed34_i_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_serie"]) || $this->ed34_i_serie != "")
             $resac = db_query("insert into db_acount values($acount,1010061,1008369,'".AddSlashes(pg_result($resaco,$conresaco,'ed34_i_serie'))."','$this->ed34_i_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_disciplina"]) || $this->ed34_i_disciplina != "")
             $resac = db_query("insert into db_acount values($acount,1010061,1008370,'".AddSlashes(pg_result($resaco,$conresaco,'ed34_i_disciplina'))."','$this->ed34_i_disciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_qtdperiodo"]) || $this->ed34_i_qtdperiodo != "")
             $resac = db_query("insert into db_acount values($acount,1010061,1008372,'".AddSlashes(pg_result($resaco,$conresaco,'ed34_i_qtdperiodo'))."','$this->ed34_i_qtdperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_chtotal"]) || $this->ed34_i_chtotal != "")
             $resac = db_query("insert into db_acount values($acount,1010061,1008371,'".AddSlashes(pg_result($resaco,$conresaco,'ed34_i_chtotal'))."','$this->ed34_i_chtotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed34_c_condicao"]) || $this->ed34_c_condicao != "")
             $resac = db_query("insert into db_acount values($acount,1010061,1008373,'".AddSlashes(pg_result($resaco,$conresaco,'ed34_c_condicao'))."','$this->ed34_c_condicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed34_i_ordenacao"]) || $this->ed34_i_ordenacao != "")
             $resac = db_query("insert into db_acount values($acount,1010061,14691,'".AddSlashes(pg_result($resaco,$conresaco,'ed34_i_ordenacao'))."','$this->ed34_i_ordenacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed34_lancarhistorico"]) || $this->ed34_lancarhistorico != "")
             $resac = db_query("insert into db_acount values($acount,1010061,20320,'".AddSlashes(pg_result($resaco,$conresaco,'ed34_lancarhistorico'))."','$this->ed34_lancarhistorico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed34_caracterreprobatorio"]) || $this->ed34_caracterreprobatorio != "")
             $resac = db_query("insert into db_acount values($acount,1010061,20657,'".AddSlashes(pg_result($resaco,$conresaco,'ed34_caracterreprobatorio'))."','$this->ed34_caracterreprobatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed34_disiciplinaglobalizada"]) || $this->ed34_disiciplinaglobalizada != "")
             $resac = db_query("insert into db_acount values($acount,1010061,20659,'".AddSlashes(pg_result($resaco,$conresaco,'ed34_disiciplinaglobalizada'))."','$this->ed34_disiciplinaglobalizada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed34_basecomum"]) || $this->ed34_basecomum != "")
             $resac = db_query("insert into db_acount values($acount,1010061,20660,'".AddSlashes(pg_result($resaco,$conresaco,'ed34_basecomum'))."','$this->ed34_basecomum',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Disciplinas da Base Curricular por Série nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed34_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Disciplinas da Base Curricular por Série nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed34_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed34_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed34_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed34_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008368,'$ed34_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010061,1008368,'','".AddSlashes(pg_result($resaco,$iresaco,'ed34_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010061,1008393,'','".AddSlashes(pg_result($resaco,$iresaco,'ed34_i_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010061,1008369,'','".AddSlashes(pg_result($resaco,$iresaco,'ed34_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010061,1008370,'','".AddSlashes(pg_result($resaco,$iresaco,'ed34_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010061,1008372,'','".AddSlashes(pg_result($resaco,$iresaco,'ed34_i_qtdperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010061,1008371,'','".AddSlashes(pg_result($resaco,$iresaco,'ed34_i_chtotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010061,1008373,'','".AddSlashes(pg_result($resaco,$iresaco,'ed34_c_condicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010061,14691,'','".AddSlashes(pg_result($resaco,$iresaco,'ed34_i_ordenacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010061,20320,'','".AddSlashes(pg_result($resaco,$iresaco,'ed34_lancarhistorico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010061,20657,'','".AddSlashes(pg_result($resaco,$iresaco,'ed34_caracterreprobatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010061,20659,'','".AddSlashes(pg_result($resaco,$iresaco,'ed34_disiciplinaglobalizada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010061,20660,'','".AddSlashes(pg_result($resaco,$iresaco,'ed34_basecomum'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from basemps
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed34_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed34_i_codigo = $ed34_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Disciplinas da Base Curricular por Série nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed34_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Disciplinas da Base Curricular por Série nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed34_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed34_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:basemps";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed34_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from basemps ";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = basemps.ed34_i_disciplina ";
     $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = basemps.ed34_i_serie ";
     $sql .= "      inner join base  on  base.ed31_i_codigo = basemps.ed34_i_base ";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino ";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed34_i_codigo!=null ){
         $sql2 .= " where basemps.ed34_i_codigo = $ed34_i_codigo ";
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
   function sql_query_file ( $ed34_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from basemps ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed34_i_codigo!=null ){
         $sql2 .= " where basemps.ed34_i_codigo = $ed34_i_codigo ";
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
   function sql_query_areaconhecimento ( $ed34_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from basemps ";
    $sql .= "      inner join disciplina        on disciplina.ed12_i_codigo = basemps.ed34_i_disciplina ";
    $sql .= "      inner join caddisciplina     on ed232_i_codigo= ed12_i_caddisciplina ";
    $sql .= "      left  join areaconhecimento  on areaconhecimento.ed293_sequencial = caddisciplina.ed232_areaconhecimento ";
    $sql .= "      inner join serie             on serie.ed11_i_codigo = basemps.ed34_i_serie ";
    $sql .= "      inner join base              on base.ed31_i_codigo = basemps.ed34_i_base ";
    $sql .= "      inner join ensino            on ensino.ed10_i_codigo = disciplina.ed12_i_ensino ";
    $sql .= "      inner join cursoedu          on cursoedu.ed29_i_codigo = base.ed31_i_curso ";
    $sql2 = "";
    if($dbwhere==""){
      if($ed34_i_codigo!=null ){
        $sql2 .= " where basemps.ed34_i_codigo = $ed34_i_codigo ";
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
  
  function sql_query_basemps_escola ( $ed34_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {
    
    $sql = "select ";
    
    if ( $campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula    = "";
      
      for ( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    
    $sql .= " from basemps ";
    $sql .= "      inner join disciplina    on  disciplina.ed12_i_codigo     = basemps.ed34_i_disciplina ";
    $sql .= "      inner join caddisciplina on  caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina ";
    $sql .= "      inner join serie         on  serie.ed11_i_codigo          = basemps.ed34_i_serie ";
    $sql .= "      inner join base          on  base.ed31_i_codigo           = basemps.ed34_i_base ";
    $sql .= "      inner join ensino        on  ensino.ed10_i_codigo         = disciplina.ed12_i_ensino ";
    $sql .= "      inner join cursoedu      on  cursoedu.ed29_i_codigo       = base.ed31_i_curso ";
    $sql .= "      inner join escolabase    on  escolabase.ed77_i_base       = base.ed31_i_codigo ";
    $sql2 = "";
    
    if ( $dbwhere == "" ) {
      
      if ( $ed34_i_codigo != null ) {
        $sql2 .= " where basemps.ed34_i_codigo = $ed34_i_codigo ";
      }
    } else if ( $dbwhere != "" ) {
      $sql2 = " where $dbwhere";
    }
    
    $sql .= $sql2;
    if ( $ordem != null ) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      
      for ( $i = 0; $i < sizeof( $campos_sql ); $i++ ) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }
}
?>