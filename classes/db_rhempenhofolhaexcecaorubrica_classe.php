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
//CLASSE DA ENTIDADE rhempenhofolhaexcecaorubrica
class cl_rhempenhofolhaexcecaorubrica { 
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
   var $rh74_sequencial = 0; 
   var $rh74_instit = 0; 
   var $rh74_rubric = null; 
   var $rh74_unidade = 0; 
   var $rh74_orgao = 0; 
   var $rh74_projativ = 0; 
   var $rh74_anousu = 0; 
   var $rh74_recurso = 0; 
   var $rh74_concarpeculiar = null; 
   var $rh74_programa = 0; 
   var $rh74_subfuncao = 0; 
   var $rh74_funcao = 0; 
   var $rh74_codele = 0; 
   var $rh74_tipofolha = 0; 
   var $rh74_rhempenhofolhaexcecaoregra = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh74_sequencial = int4 = Sequencial 
                 rh74_instit = int4 = Instituição 
                 rh74_rubric = char(4) = Rubrica 
                 rh74_unidade = int4 = Unidade 
                 rh74_orgao = int4 = Órgão 
                 rh74_projativ = int4 = Projetos / Atividades 
                 rh74_anousu = int4 = Exercício 
                 rh74_recurso = int4 = Recurso 
                 rh74_concarpeculiar = varchar(100) = Característica Peculiar 
                 rh74_programa = int4 = Programa 
                 rh74_subfuncao = int4 = Subfunção 
                 rh74_funcao = int4 = Função 
                 rh74_codele = int4 = Desdobramento 
                 rh74_tipofolha = int4 = Tipo Folha 
                 rh74_rhempenhofolhaexcecaoregra = int4 = Código Exceção Rubrica 
                 ";
   //funcao construtor da classe 
   function cl_rhempenhofolhaexcecaorubrica() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhempenhofolhaexcecaorubrica"); 
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
       $this->rh74_sequencial = ($this->rh74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_sequencial"]:$this->rh74_sequencial);
       $this->rh74_instit = ($this->rh74_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_instit"]:$this->rh74_instit);
       $this->rh74_rubric = ($this->rh74_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_rubric"]:$this->rh74_rubric);
       $this->rh74_unidade = ($this->rh74_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_unidade"]:$this->rh74_unidade);
       $this->rh74_orgao = ($this->rh74_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_orgao"]:$this->rh74_orgao);
       $this->rh74_projativ = ($this->rh74_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_projativ"]:$this->rh74_projativ);
       $this->rh74_anousu = ($this->rh74_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_anousu"]:$this->rh74_anousu);
       $this->rh74_recurso = ($this->rh74_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_recurso"]:$this->rh74_recurso);
       $this->rh74_concarpeculiar = ($this->rh74_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_concarpeculiar"]:$this->rh74_concarpeculiar);
       $this->rh74_programa = ($this->rh74_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_programa"]:$this->rh74_programa);
       $this->rh74_subfuncao = ($this->rh74_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_subfuncao"]:$this->rh74_subfuncao);
       $this->rh74_funcao = ($this->rh74_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_funcao"]:$this->rh74_funcao);
       $this->rh74_codele = ($this->rh74_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_codele"]:$this->rh74_codele);
       $this->rh74_tipofolha = ($this->rh74_tipofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_tipofolha"]:$this->rh74_tipofolha);
       $this->rh74_rhempenhofolhaexcecaoregra = ($this->rh74_rhempenhofolhaexcecaoregra == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_rhempenhofolhaexcecaoregra"]:$this->rh74_rhempenhofolhaexcecaoregra);
     }else{
       $this->rh74_sequencial = ($this->rh74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh74_sequencial"]:$this->rh74_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh74_sequencial){ 
      $this->atualizacampos();
     if($this->rh74_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh74_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh74_rubric == null ){ 
       $this->erro_sql = " Campo Rubrica não informado.";
       $this->erro_campo = "rh74_rubric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh74_unidade == null ){ 
       $this->erro_sql = " Campo Unidade não informado.";
       $this->erro_campo = "rh74_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh74_orgao == null ){ 
       $this->erro_sql = " Campo Órgão não informado.";
       $this->erro_campo = "rh74_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh74_projativ == null ){ 
       $this->erro_sql = " Campo Projetos / Atividades não informado.";
       $this->erro_campo = "rh74_projativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh74_anousu == null ){ 
       $this->erro_sql = " Campo Exercício não informado.";
       $this->erro_campo = "rh74_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh74_recurso == null ){ 
       $this->erro_sql = " Campo Recurso não informado.";
       $this->erro_campo = "rh74_recurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh74_programa == null ){ 
       $this->rh74_programa = "null";
     }
     if($this->rh74_subfuncao == null ){ 
       $this->rh74_subfuncao = "null";
     }
     if($this->rh74_funcao == null ){ 
       $this->rh74_funcao = "null";
     }
     if($this->rh74_codele == null ){ 
       $this->rh74_codele = "null";
     }
     if($this->rh74_tipofolha == null ){ 
       $this->rh74_tipofolha = "0";
     }
     if($this->rh74_rhempenhofolhaexcecaoregra == null ){ 
       $this->erro_sql = " Campo Código Exceção Rubrica não informado.";
       $this->erro_campo = "rh74_rhempenhofolhaexcecaoregra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh74_sequencial == "" || $rh74_sequencial == null ){
       $result = db_query("select nextval('rhempenhofolhaexcecaorubrica_rh74_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhempenhofolhaexcecaorubrica_rh74_sequencial_seq do campo: rh74_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh74_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhempenhofolhaexcecaorubrica_rh74_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh74_sequencial)){
         $this->erro_sql = " Campo rh74_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh74_sequencial = $rh74_sequencial; 
       }
     }
     if(($this->rh74_sequencial == null) || ($this->rh74_sequencial == "") ){ 
       $this->erro_sql = " Campo rh74_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhempenhofolhaexcecaorubrica(
                                       rh74_sequencial 
                                      ,rh74_instit 
                                      ,rh74_rubric 
                                      ,rh74_unidade 
                                      ,rh74_orgao 
                                      ,rh74_projativ 
                                      ,rh74_anousu 
                                      ,rh74_recurso 
                                      ,rh74_concarpeculiar 
                                      ,rh74_programa 
                                      ,rh74_subfuncao 
                                      ,rh74_funcao 
                                      ,rh74_codele 
                                      ,rh74_tipofolha 
                                      ,rh74_rhempenhofolhaexcecaoregra 
                       )
                values (
                                $this->rh74_sequencial 
                               ,$this->rh74_instit 
                               ,'$this->rh74_rubric' 
                               ,$this->rh74_unidade 
                               ,$this->rh74_orgao 
                               ,$this->rh74_projativ 
                               ,$this->rh74_anousu 
                               ,$this->rh74_recurso 
                               ,'$this->rh74_concarpeculiar' 
                               ,$this->rh74_programa 
                               ,$this->rh74_subfuncao 
                               ,$this->rh74_funcao 
                               ,$this->rh74_codele 
                               ,$this->rh74_tipofolha 
                               ,$this->rh74_rhempenhofolhaexcecaoregra 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhempenhofolhaexcecaorubrica ($this->rh74_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhempenhofolhaexcecaorubrica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhempenhofolhaexcecaorubrica ($this->rh74_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh74_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh74_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14257,'$this->rh74_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2508,14257,'','".AddSlashes(pg_result($resaco,0,'rh74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,14259,'','".AddSlashes(pg_result($resaco,0,'rh74_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,14258,'','".AddSlashes(pg_result($resaco,0,'rh74_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,14260,'','".AddSlashes(pg_result($resaco,0,'rh74_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,14261,'','".AddSlashes(pg_result($resaco,0,'rh74_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,14262,'','".AddSlashes(pg_result($resaco,0,'rh74_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,14263,'','".AddSlashes(pg_result($resaco,0,'rh74_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,14264,'','".AddSlashes(pg_result($resaco,0,'rh74_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,15051,'','".AddSlashes(pg_result($resaco,0,'rh74_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,19162,'','".AddSlashes(pg_result($resaco,0,'rh74_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,19163,'','".AddSlashes(pg_result($resaco,0,'rh74_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,19164,'','".AddSlashes(pg_result($resaco,0,'rh74_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,20340,'','".AddSlashes(pg_result($resaco,0,'rh74_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,20339,'','".AddSlashes(pg_result($resaco,0,'rh74_tipofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2508,20344,'','".AddSlashes(pg_result($resaco,0,'rh74_rhempenhofolhaexcecaoregra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh74_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhempenhofolhaexcecaorubrica set ";
     $virgula = "";
     if(trim($this->rh74_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_sequencial"])){ 
       $sql  .= $virgula." rh74_sequencial = $this->rh74_sequencial ";
       $virgula = ",";
       if(trim($this->rh74_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh74_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh74_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_instit"])){ 
       $sql  .= $virgula." rh74_instit = $this->rh74_instit ";
       $virgula = ",";
       if(trim($this->rh74_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh74_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh74_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_rubric"])){ 
       $sql  .= $virgula." rh74_rubric = '$this->rh74_rubric' ";
       $virgula = ",";
       if(trim($this->rh74_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "rh74_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh74_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_unidade"])){ 
       $sql  .= $virgula." rh74_unidade = $this->rh74_unidade ";
       $virgula = ",";
       if(trim($this->rh74_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade não informado.";
         $this->erro_campo = "rh74_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh74_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_orgao"])){ 
       $sql  .= $virgula." rh74_orgao = $this->rh74_orgao ";
       $virgula = ",";
       if(trim($this->rh74_orgao) == null ){ 
         $this->erro_sql = " Campo Órgão não informado.";
         $this->erro_campo = "rh74_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh74_projativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_projativ"])){ 
       $sql  .= $virgula." rh74_projativ = $this->rh74_projativ ";
       $virgula = ",";
       if(trim($this->rh74_projativ) == null ){ 
         $this->erro_sql = " Campo Projetos / Atividades não informado.";
         $this->erro_campo = "rh74_projativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh74_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_anousu"])){ 
       $sql  .= $virgula." rh74_anousu = $this->rh74_anousu ";
       $virgula = ",";
       if(trim($this->rh74_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício não informado.";
         $this->erro_campo = "rh74_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh74_recurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_recurso"])){ 
       $sql  .= $virgula." rh74_recurso = $this->rh74_recurso ";
       $virgula = ",";
       if(trim($this->rh74_recurso) == null ){ 
         $this->erro_sql = " Campo Recurso não informado.";
         $this->erro_campo = "rh74_recurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh74_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_concarpeculiar"])){ 
       $sql  .= $virgula." rh74_concarpeculiar = '$this->rh74_concarpeculiar' ";
       $virgula = ",";
     }
     if(trim($this->rh74_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_programa"])){ 
        if(trim($this->rh74_programa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh74_programa"])){ 
           $this->rh74_programa = "0" ; 
        } 
       $sql  .= $virgula." rh74_programa = $this->rh74_programa ";
       $virgula = ",";
     }
     if(trim($this->rh74_subfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_subfuncao"])){ 
        if(trim($this->rh74_subfuncao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh74_subfuncao"])){ 
           $this->rh74_subfuncao = "0" ; 
        } 
       $sql  .= $virgula." rh74_subfuncao = $this->rh74_subfuncao ";
       $virgula = ",";
     }
     if(trim($this->rh74_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_funcao"])){ 
        if(trim($this->rh74_funcao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh74_funcao"])){ 
           $this->rh74_funcao = "0" ; 
        } 
       $sql  .= $virgula." rh74_funcao = $this->rh74_funcao ";
       $virgula = ",";
     }
     if(trim($this->rh74_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_codele"])){ 
        if(trim($this->rh74_codele)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh74_codele"])){ 
           $this->rh74_codele = "0" ; 
        } 
       $sql  .= $virgula." rh74_codele = $this->rh74_codele ";
       $virgula = ",";
     }
     if(trim($this->rh74_tipofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_tipofolha"])){ 
        if(trim($this->rh74_tipofolha)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh74_tipofolha"])){ 
           $this->rh74_tipofolha = "0" ; 
        } 
       $sql  .= $virgula." rh74_tipofolha = $this->rh74_tipofolha ";
       $virgula = ",";
     }
     if(trim($this->rh74_rhempenhofolhaexcecaoregra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh74_rhempenhofolhaexcecaoregra"])){ 
       $sql  .= $virgula." rh74_rhempenhofolhaexcecaoregra = $this->rh74_rhempenhofolhaexcecaoregra ";
       $virgula = ",";
       if(trim($this->rh74_rhempenhofolhaexcecaoregra) == null ){ 
         $this->erro_sql = " Campo Código Exceção Rubrica não informado.";
         $this->erro_campo = "rh74_rhempenhofolhaexcecaoregra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh74_sequencial!=null){
       $sql .= " rh74_sequencial = $this->rh74_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh74_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,14257,'$this->rh74_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_sequencial"]) || $this->rh74_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2508,14257,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_sequencial'))."','$this->rh74_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_instit"]) || $this->rh74_instit != "")
             $resac = db_query("insert into db_acount values($acount,2508,14259,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_instit'))."','$this->rh74_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_rubric"]) || $this->rh74_rubric != "")
             $resac = db_query("insert into db_acount values($acount,2508,14258,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_rubric'))."','$this->rh74_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_unidade"]) || $this->rh74_unidade != "")
             $resac = db_query("insert into db_acount values($acount,2508,14260,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_unidade'))."','$this->rh74_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_orgao"]) || $this->rh74_orgao != "")
             $resac = db_query("insert into db_acount values($acount,2508,14261,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_orgao'))."','$this->rh74_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_projativ"]) || $this->rh74_projativ != "")
             $resac = db_query("insert into db_acount values($acount,2508,14262,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_projativ'))."','$this->rh74_projativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_anousu"]) || $this->rh74_anousu != "")
             $resac = db_query("insert into db_acount values($acount,2508,14263,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_anousu'))."','$this->rh74_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_recurso"]) || $this->rh74_recurso != "")
             $resac = db_query("insert into db_acount values($acount,2508,14264,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_recurso'))."','$this->rh74_recurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_concarpeculiar"]) || $this->rh74_concarpeculiar != "")
             $resac = db_query("insert into db_acount values($acount,2508,15051,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_concarpeculiar'))."','$this->rh74_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_programa"]) || $this->rh74_programa != "")
             $resac = db_query("insert into db_acount values($acount,2508,19162,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_programa'))."','$this->rh74_programa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_subfuncao"]) || $this->rh74_subfuncao != "")
             $resac = db_query("insert into db_acount values($acount,2508,19163,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_subfuncao'))."','$this->rh74_subfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_funcao"]) || $this->rh74_funcao != "")
             $resac = db_query("insert into db_acount values($acount,2508,19164,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_funcao'))."','$this->rh74_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_codele"]) || $this->rh74_codele != "")
             $resac = db_query("insert into db_acount values($acount,2508,20340,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_codele'))."','$this->rh74_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_tipofolha"]) || $this->rh74_tipofolha != "")
             $resac = db_query("insert into db_acount values($acount,2508,20339,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_tipofolha'))."','$this->rh74_tipofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh74_rhempenhofolhaexcecaoregra"]) || $this->rh74_rhempenhofolhaexcecaoregra != "")
             $resac = db_query("insert into db_acount values($acount,2508,20344,'".AddSlashes(pg_result($resaco,$conresaco,'rh74_rhempenhofolhaexcecaoregra'))."','$this->rh74_rhempenhofolhaexcecaoregra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhempenhofolhaexcecaorubrica nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh74_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhempenhofolhaexcecaorubrica nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh74_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh74_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh74_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh74_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,14257,'$rh74_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2508,14257,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,14259,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,14258,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,14260,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,14261,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,14262,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,14263,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,14264,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,15051,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,19162,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,19163,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,19164,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,20340,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,20339,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_tipofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2508,20344,'','".AddSlashes(pg_result($resaco,$iresaco,'rh74_rhempenhofolhaexcecaoregra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhempenhofolhaexcecaorubrica
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh74_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh74_sequencial = $rh74_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhempenhofolhaexcecaorubrica nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh74_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhempenhofolhaexcecaorubrica nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh74_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh74_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhempenhofolhaexcecaorubrica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolhaexcecaorubrica ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhempenhofolhaexcecaorubrica.rh74_recurso";
     $sql .= "      left  join orcfuncao  on  orcfuncao.o52_funcao = rhempenhofolhaexcecaorubrica.rh74_funcao";
     $sql .= "      left  join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = rhempenhofolhaexcecaorubrica.rh74_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = rhempenhofolhaexcecaorubrica.rh74_anousu and  orcprograma.o54_programa = rhempenhofolhaexcecaorubrica.rh74_programa";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = rhempenhofolhaexcecaorubrica.rh74_codele and  orcelemento.o56_anousu = rhempenhofolhaexcecaorubrica.rh74_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = rhempenhofolhaexcecaorubrica.rh74_anousu and  orcprojativ.o55_projativ = rhempenhofolhaexcecaorubrica.rh74_projativ";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = rhempenhofolhaexcecaorubrica.rh74_anousu and  orcunidade.o41_orgao = rhempenhofolhaexcecaorubrica.rh74_orgao and  orcunidade.o41_unidade = rhempenhofolhaexcecaorubrica.rh74_unidade";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhempenhofolhaexcecaorubrica.rh74_rubric and  rhrubricas.rh27_instit = rhempenhofolhaexcecaorubrica.rh74_instit";
     $sql .= "      left  join concarpeculiar  on  concarpeculiar.c58_sequencial = rhempenhofolhaexcecaorubrica.rh74_concarpeculiar";
     $sql .= "      inner join rhempenhofolhaexcecaoregra  on  rhempenhofolhaexcecaoregra.rh128_sequencial = rhempenhofolhaexcecaorubrica.rh74_rhempenhofolhaexcecaoregra";
     $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";
     $sql .= "      inner join db_config  on  db_config.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcprojativ.o55_orcproduto";
     $sql .= "      inner join db_config  as a on   a.codigo = orcunidade.o41_instit";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcunidade.o41_anousu and  orcorgao.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      inner join db_config  as b on   b.codigo = rhrubricas.rh27_instit";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql .= "      inner join db_estruturavalor  as c on   c.db121_sequencial = concarpeculiar.c58_db_estruturavalor";
     $sql .= "      inner join concarpeculiarclassificacao  on  concarpeculiarclassificacao.c09_sequencial = concarpeculiar.c58_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if($rh74_sequencial!=null ){
         $sql2 .= " where rhempenhofolhaexcecaorubrica.rh74_sequencial = $rh74_sequencial "; 
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
   function sql_query_file ( $rh74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolhaexcecaorubrica ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh74_sequencial!=null ){
         $sql2 .= " where rhempenhofolhaexcecaorubrica.rh74_sequencial = $rh74_sequencial "; 
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

  function sql_query_rubricas($iExcecaoRegra = "", $iAnousu = "", $iInstit = "", $iTipoFolha = "") {

    $sSql  = "select rh27_rubric, rh27_descr, (rh74_rubric is not null) as lSelecionado "; 
    $sSql .= "  from rhrubricas                                                         "; 
    $sSql .= "   left join rhempenhofolhaexcecaorubrica                                 ";
    $sSql .= "             on rh74_rubric    = rh27_rubric                              ";
    $sSql .= "            and rh74_anousu    = $iAnousu                                 ";
    $sSql .= "            and rh74_tipofolha = $iTipoFolha                              ";
    $sSql .= "            and rh74_instit    = rh27_instit                              ";
    $sSql .= " where rh27_instit = $iInstit                                             ";
    $sSql .= "   and (";
    $sSql .= "        rh74_rubric is null";

    if ($iExcecaoRegra != "") {
      $sSql .= " or rh74_rhempenhofolhaexcecaoregra = $iExcecaoRegra"; 
    }

    $sSql .= "       )";

    $sSql .= "order by rh27_rubric";

    return $sSql;
  }
}
?>