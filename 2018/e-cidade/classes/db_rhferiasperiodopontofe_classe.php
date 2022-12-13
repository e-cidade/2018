<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhferiasperiodopontofe
class cl_rhferiasperiodopontofe { 
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
   var $rh112_sequencial = 0; 
   var $rh112_rhferiasperiodo = 0; 
   var $rh112_anousu = 0; 
   var $rh112_mesusu = 0; 
   var $rh112_regist = 0; 
   var $rh112_rubric = null; 
   var $rh112_tpp = null; 
   var $rh112_quantidade = 0; 
   var $rh112_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh112_sequencial = int4 = Sequencial 
                 rh112_rhferiasperiodo = int4 = Periodo de ferias 
                 rh112_anousu = int4 = Ano 
                 rh112_mesusu = int4 = Mês 
                 rh112_regist = int4 = Matricula 
                 rh112_rubric = char(4) = Rubrica 
                 rh112_tpp = char(1) = Tipo de ponto 
                 rh112_quantidade = float8 = Quantidade 
                 rh112_valor = float8 = Valor 
                 ";
   //funcao construtor da classe 
   function cl_rhferiasperiodopontofe() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhferiasperiodopontofe"); 
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
       $this->rh112_sequencial = ($this->rh112_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh112_sequencial"]:$this->rh112_sequencial);
       $this->rh112_rhferiasperiodo = ($this->rh112_rhferiasperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh112_rhferiasperiodo"]:$this->rh112_rhferiasperiodo);
       $this->rh112_anousu = ($this->rh112_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh112_anousu"]:$this->rh112_anousu);
       $this->rh112_mesusu = ($this->rh112_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh112_mesusu"]:$this->rh112_mesusu);
       $this->rh112_regist = ($this->rh112_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh112_regist"]:$this->rh112_regist);
       $this->rh112_rubric = ($this->rh112_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh112_rubric"]:$this->rh112_rubric);
       $this->rh112_tpp = ($this->rh112_tpp == ""?@$GLOBALS["HTTP_POST_VARS"]["rh112_tpp"]:$this->rh112_tpp);
       $this->rh112_quantidade = ($this->rh112_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh112_quantidade"]:$this->rh112_quantidade);
       $this->rh112_valor = ($this->rh112_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh112_valor"]:$this->rh112_valor);
     }else{
       $this->rh112_sequencial = ($this->rh112_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh112_sequencial"]:$this->rh112_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh112_sequencial){ 
      $this->atualizacampos();
     if($this->rh112_rhferiasperiodo == null ){ 
       $this->erro_sql = " Campo Periodo de ferias nao Informado.";
       $this->erro_campo = "rh112_rhferiasperiodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh112_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "rh112_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh112_mesusu == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "rh112_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh112_regist == null ){ 
       $this->erro_sql = " Campo Matricula nao Informado.";
       $this->erro_campo = "rh112_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh112_rubric == null ){ 
       $this->erro_sql = " Campo Rubrica nao Informado.";
       $this->erro_campo = "rh112_rubric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh112_tpp == null ){ 
       $this->erro_sql = " Campo Tipo de ponto nao Informado.";
       $this->erro_campo = "rh112_tpp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh112_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "rh112_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh112_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "rh112_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh112_sequencial == "" || $rh112_sequencial == null ){
       $result = db_query("select nextval('rhferiasperiodopontofe_rh112_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhferiasperiodopontofe_rh112_sequencial_seq do campo: rh112_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh112_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhferiasperiodopontofe_rh112_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh112_sequencial)){
         $this->erro_sql = " Campo rh112_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh112_sequencial = $rh112_sequencial; 
       }
     }
     if(($this->rh112_sequencial == null) || ($this->rh112_sequencial == "") ){ 
       $this->erro_sql = " Campo rh112_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhferiasperiodopontofe(
                                       rh112_sequencial 
                                      ,rh112_rhferiasperiodo 
                                      ,rh112_anousu 
                                      ,rh112_mesusu 
                                      ,rh112_regist 
                                      ,rh112_rubric 
                                      ,rh112_tpp 
                                      ,rh112_quantidade 
                                      ,rh112_valor 
                       )
                values (
                                $this->rh112_sequencial 
                               ,$this->rh112_rhferiasperiodo 
                               ,$this->rh112_anousu 
                               ,$this->rh112_mesusu 
                               ,$this->rh112_regist 
                               ,'$this->rh112_rubric' 
                               ,'$this->rh112_tpp' 
                               ,$this->rh112_quantidade 
                               ,$this->rh112_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Periodo de ferias do ponto ($this->rh112_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Periodo de ferias do ponto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Periodo de ferias do ponto ($this->rh112_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh112_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh112_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19332,'$this->rh112_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3436,19332,'','".AddSlashes(pg_result($resaco,0,'rh112_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3436,19333,'','".AddSlashes(pg_result($resaco,0,'rh112_rhferiasperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3436,19340,'','".AddSlashes(pg_result($resaco,0,'rh112_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3436,19341,'','".AddSlashes(pg_result($resaco,0,'rh112_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3436,19343,'','".AddSlashes(pg_result($resaco,0,'rh112_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3436,19344,'','".AddSlashes(pg_result($resaco,0,'rh112_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3436,19346,'','".AddSlashes(pg_result($resaco,0,'rh112_tpp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3436,20164,'','".AddSlashes(pg_result($resaco,0,'rh112_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3436,20165,'','".AddSlashes(pg_result($resaco,0,'rh112_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh112_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhferiasperiodopontofe set ";
     $virgula = "";
     if(trim($this->rh112_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh112_sequencial"])){ 
       $sql  .= $virgula." rh112_sequencial = $this->rh112_sequencial ";
       $virgula = ",";
       if(trim($this->rh112_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh112_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh112_rhferiasperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh112_rhferiasperiodo"])){ 
       $sql  .= $virgula." rh112_rhferiasperiodo = $this->rh112_rhferiasperiodo ";
       $virgula = ",";
       if(trim($this->rh112_rhferiasperiodo) == null ){ 
         $this->erro_sql = " Campo Periodo de ferias nao Informado.";
         $this->erro_campo = "rh112_rhferiasperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh112_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh112_anousu"])){ 
       $sql  .= $virgula." rh112_anousu = $this->rh112_anousu ";
       $virgula = ",";
       if(trim($this->rh112_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "rh112_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh112_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh112_mesusu"])){ 
       $sql  .= $virgula." rh112_mesusu = $this->rh112_mesusu ";
       $virgula = ",";
       if(trim($this->rh112_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "rh112_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh112_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh112_regist"])){ 
       $sql  .= $virgula." rh112_regist = $this->rh112_regist ";
       $virgula = ",";
       if(trim($this->rh112_regist) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "rh112_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh112_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh112_rubric"])){ 
       $sql  .= $virgula." rh112_rubric = '$this->rh112_rubric' ";
       $virgula = ",";
       if(trim($this->rh112_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "rh112_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh112_tpp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh112_tpp"])){ 
       $sql  .= $virgula." rh112_tpp = '$this->rh112_tpp' ";
       $virgula = ",";
       if(trim($this->rh112_tpp) == null ){ 
         $this->erro_sql = " Campo Tipo de ponto nao Informado.";
         $this->erro_campo = "rh112_tpp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh112_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh112_quantidade"])){ 
       $sql  .= $virgula." rh112_quantidade = $this->rh112_quantidade ";
       $virgula = ",";
       if(trim($this->rh112_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "rh112_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh112_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh112_valor"])){ 
       $sql  .= $virgula." rh112_valor = $this->rh112_valor ";
       $virgula = ",";
       if(trim($this->rh112_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "rh112_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh112_sequencial!=null){
       $sql .= " rh112_sequencial = $this->rh112_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh112_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19332,'$this->rh112_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh112_sequencial"]) || $this->rh112_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3436,19332,'".AddSlashes(pg_result($resaco,$conresaco,'rh112_sequencial'))."','$this->rh112_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh112_rhferiasperiodo"]) || $this->rh112_rhferiasperiodo != "")
             $resac = db_query("insert into db_acount values($acount,3436,19333,'".AddSlashes(pg_result($resaco,$conresaco,'rh112_rhferiasperiodo'))."','$this->rh112_rhferiasperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh112_anousu"]) || $this->rh112_anousu != "")
             $resac = db_query("insert into db_acount values($acount,3436,19340,'".AddSlashes(pg_result($resaco,$conresaco,'rh112_anousu'))."','$this->rh112_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh112_mesusu"]) || $this->rh112_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,3436,19341,'".AddSlashes(pg_result($resaco,$conresaco,'rh112_mesusu'))."','$this->rh112_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh112_regist"]) || $this->rh112_regist != "")
             $resac = db_query("insert into db_acount values($acount,3436,19343,'".AddSlashes(pg_result($resaco,$conresaco,'rh112_regist'))."','$this->rh112_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh112_rubric"]) || $this->rh112_rubric != "")
             $resac = db_query("insert into db_acount values($acount,3436,19344,'".AddSlashes(pg_result($resaco,$conresaco,'rh112_rubric'))."','$this->rh112_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh112_tpp"]) || $this->rh112_tpp != "")
             $resac = db_query("insert into db_acount values($acount,3436,19346,'".AddSlashes(pg_result($resaco,$conresaco,'rh112_tpp'))."','$this->rh112_tpp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh112_quantidade"]) || $this->rh112_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,3436,20164,'".AddSlashes(pg_result($resaco,$conresaco,'rh112_quantidade'))."','$this->rh112_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh112_valor"]) || $this->rh112_valor != "")
             $resac = db_query("insert into db_acount values($acount,3436,20165,'".AddSlashes(pg_result($resaco,$conresaco,'rh112_valor'))."','$this->rh112_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Periodo de ferias do ponto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh112_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Periodo de ferias do ponto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh112_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh112_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh112_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh112_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19332,'$rh112_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3436,19332,'','".AddSlashes(pg_result($resaco,$iresaco,'rh112_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3436,19333,'','".AddSlashes(pg_result($resaco,$iresaco,'rh112_rhferiasperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3436,19340,'','".AddSlashes(pg_result($resaco,$iresaco,'rh112_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3436,19341,'','".AddSlashes(pg_result($resaco,$iresaco,'rh112_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3436,19343,'','".AddSlashes(pg_result($resaco,$iresaco,'rh112_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3436,19344,'','".AddSlashes(pg_result($resaco,$iresaco,'rh112_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3436,19346,'','".AddSlashes(pg_result($resaco,$iresaco,'rh112_tpp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3436,20164,'','".AddSlashes(pg_result($resaco,$iresaco,'rh112_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3436,20165,'','".AddSlashes(pg_result($resaco,$iresaco,'rh112_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhferiasperiodopontofe
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh112_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh112_sequencial = $rh112_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Periodo de ferias do ponto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh112_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Periodo de ferias do ponto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh112_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh112_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhferiasperiodopontofe";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh112_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhferiasperiodopontofe ";
     $sql .= "      inner join rhferiasperiodo  on  rhferiasperiodo.rh110_sequencial = rhferiasperiodopontofe.rh112_rhferiasperiodo";
     $sql .= "      inner join rhferias  on  rhferias.rh109_sequencial = rhferiasperiodo.rh110_rhferias";
     $sql2 = "";
     if($dbwhere==""){
       if($rh112_sequencial!=null ){
         $sql2 .= " where rhferiasperiodopontofe.rh112_sequencial = $rh112_sequencial "; 
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
   function sql_query_file ( $rh112_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhferiasperiodopontofe ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh112_sequencial!=null ){
         $sql2 .= " where rhferiasperiodopontofe.rh112_sequencial = $rh112_sequencial "; 
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