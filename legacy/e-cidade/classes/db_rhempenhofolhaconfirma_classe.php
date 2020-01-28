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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhempenhofolhaconfirma
class cl_rhempenhofolhaconfirma { 
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
   var $rh83_sequencial = 0; 
   var $rh83_id_usuario = 0; 
   var $rh83_mesusu = 0; 
   var $rh83_anousu = 0; 
   var $rh83_siglaarq = null; 
   var $rh83_complementar = 0; 
   var $rh83_dataliberacao_dia = null; 
   var $rh83_dataliberacao_mes = null; 
   var $rh83_dataliberacao_ano = null; 
   var $rh83_dataliberacao = null; 
   var $rh83_tipoempenho = 0; 
   var $rh83_tabprev = 0; 
   var $rh83_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh83_sequencial = int4 = Código Sequencial 
                 rh83_id_usuario = int4 = Cod. Usuário 
                 rh83_mesusu = int4 = Mê 
                 rh83_anousu = int4 = Ano 
                 rh83_siglaarq = char(3) = Tipo de Folha 
                 rh83_complementar = int4 = Complementar da Folha 
                 rh83_dataliberacao = date = Data da Liberação 
                 rh83_tipoempenho = int4 = Tipo de Empenho 
                 rh83_tabprev = int4 = Previdencia 
                 rh83_instit = int4 = Instituição 
                 ";
   //funcao construtor da classe 
   function cl_rhempenhofolhaconfirma() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhempenhofolhaconfirma"); 
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
       $this->rh83_sequencial = ($this->rh83_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_sequencial"]:$this->rh83_sequencial);
       $this->rh83_id_usuario = ($this->rh83_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_id_usuario"]:$this->rh83_id_usuario);
       $this->rh83_mesusu = ($this->rh83_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_mesusu"]:$this->rh83_mesusu);
       $this->rh83_anousu = ($this->rh83_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_anousu"]:$this->rh83_anousu);
       $this->rh83_siglaarq = ($this->rh83_siglaarq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_siglaarq"]:$this->rh83_siglaarq);
       $this->rh83_complementar = ($this->rh83_complementar == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_complementar"]:$this->rh83_complementar);
       if($this->rh83_dataliberacao == ""){
         $this->rh83_dataliberacao_dia = ($this->rh83_dataliberacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_dataliberacao_dia"]:$this->rh83_dataliberacao_dia);
         $this->rh83_dataliberacao_mes = ($this->rh83_dataliberacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_dataliberacao_mes"]:$this->rh83_dataliberacao_mes);
         $this->rh83_dataliberacao_ano = ($this->rh83_dataliberacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_dataliberacao_ano"]:$this->rh83_dataliberacao_ano);
         if($this->rh83_dataliberacao_dia != ""){
            $this->rh83_dataliberacao = $this->rh83_dataliberacao_ano."-".$this->rh83_dataliberacao_mes."-".$this->rh83_dataliberacao_dia;
         }
       }
       $this->rh83_tipoempenho = ($this->rh83_tipoempenho == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_tipoempenho"]:$this->rh83_tipoempenho);
       $this->rh83_tabprev = ($this->rh83_tabprev == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_tabprev"]:$this->rh83_tabprev);
       $this->rh83_instit = ($this->rh83_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_instit"]:$this->rh83_instit);
     }else{
       $this->rh83_sequencial = ($this->rh83_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh83_sequencial"]:$this->rh83_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh83_sequencial){ 
      $this->atualizacampos();
     if($this->rh83_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "rh83_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh83_mesusu == null ){ 
       $this->erro_sql = " Campo Mê nao Informado.";
       $this->erro_campo = "rh83_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh83_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "rh83_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh83_siglaarq == null ){ 
       $this->erro_sql = " Campo Tipo de Folha nao Informado.";
       $this->erro_campo = "rh83_siglaarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh83_complementar == null ){ 
       $this->rh83_complementar = "0";
     }
     if($this->rh83_dataliberacao == null ){ 
       $this->rh83_dataliberacao = "null";
     }
     if($this->rh83_tipoempenho == null ){ 
       $this->erro_sql = " Campo Tipo de Empenho nao Informado.";
       $this->erro_campo = "rh83_tipoempenho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh83_tabprev == null ){ 
       $this->rh83_tabprev = "0";
     }
     if($this->rh83_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "rh83_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh83_sequencial == "" || $rh83_sequencial == null ){
       $result = db_query("select nextval('rhempenhofolhaconfirma_rh83_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhempenhofolhaconfirma_rh83_sequencial_seq do campo: rh83_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh83_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhempenhofolhaconfirma_rh83_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh83_sequencial)){
         $this->erro_sql = " Campo rh83_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh83_sequencial = $rh83_sequencial; 
       }
     }
     if(($this->rh83_sequencial == null) || ($this->rh83_sequencial == "") ){ 
       $this->erro_sql = " Campo rh83_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhempenhofolhaconfirma(
                                       rh83_sequencial 
                                      ,rh83_id_usuario 
                                      ,rh83_mesusu 
                                      ,rh83_anousu 
                                      ,rh83_siglaarq 
                                      ,rh83_complementar 
                                      ,rh83_dataliberacao 
                                      ,rh83_tipoempenho 
                                      ,rh83_tabprev 
                                      ,rh83_instit 
                       )
                values (
                                $this->rh83_sequencial 
                               ,$this->rh83_id_usuario 
                               ,$this->rh83_mesusu 
                               ,$this->rh83_anousu 
                               ,'$this->rh83_siglaarq' 
                               ,$this->rh83_complementar 
                               ,".($this->rh83_dataliberacao == "null" || $this->rh83_dataliberacao == ""?"null":"'".$this->rh83_dataliberacao."'")." 
                               ,$this->rh83_tipoempenho 
                               ,$this->rh83_tabprev 
                               ,$this->rh83_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Liberação dos empenhos da folha ($this->rh83_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Liberação dos empenhos da folha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Liberação dos empenhos da folha ($this->rh83_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh83_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh83_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14446,'$this->rh83_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2548,14446,'','".AddSlashes(pg_result($resaco,0,'rh83_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2548,14447,'','".AddSlashes(pg_result($resaco,0,'rh83_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2548,14449,'','".AddSlashes(pg_result($resaco,0,'rh83_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2548,14448,'','".AddSlashes(pg_result($resaco,0,'rh83_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2548,14450,'','".AddSlashes(pg_result($resaco,0,'rh83_siglaarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2548,14451,'','".AddSlashes(pg_result($resaco,0,'rh83_complementar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2548,14452,'','".AddSlashes(pg_result($resaco,0,'rh83_dataliberacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2548,14460,'','".AddSlashes(pg_result($resaco,0,'rh83_tipoempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2548,19215,'','".AddSlashes(pg_result($resaco,0,'rh83_tabprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2548,15591,'','".AddSlashes(pg_result($resaco,0,'rh83_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh83_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhempenhofolhaconfirma set ";
     $virgula = "";
     if(trim($this->rh83_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh83_sequencial"])){ 
       $sql  .= $virgula." rh83_sequencial = $this->rh83_sequencial ";
       $virgula = ",";
       if(trim($this->rh83_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "rh83_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh83_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh83_id_usuario"])){ 
       $sql  .= $virgula." rh83_id_usuario = $this->rh83_id_usuario ";
       $virgula = ",";
       if(trim($this->rh83_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "rh83_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh83_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh83_mesusu"])){ 
       $sql  .= $virgula." rh83_mesusu = $this->rh83_mesusu ";
       $virgula = ",";
       if(trim($this->rh83_mesusu) == null ){ 
         $this->erro_sql = " Campo Mê nao Informado.";
         $this->erro_campo = "rh83_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh83_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh83_anousu"])){ 
       $sql  .= $virgula." rh83_anousu = $this->rh83_anousu ";
       $virgula = ",";
       if(trim($this->rh83_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "rh83_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh83_siglaarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh83_siglaarq"])){ 
       $sql  .= $virgula." rh83_siglaarq = '$this->rh83_siglaarq' ";
       $virgula = ",";
       if(trim($this->rh83_siglaarq) == null ){ 
         $this->erro_sql = " Campo Tipo de Folha nao Informado.";
         $this->erro_campo = "rh83_siglaarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh83_complementar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh83_complementar"])){ 
        if(trim($this->rh83_complementar)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh83_complementar"])){ 
           $this->rh83_complementar = "0" ; 
        } 
       $sql  .= $virgula." rh83_complementar = $this->rh83_complementar ";
       $virgula = ",";
     }
     if(trim($this->rh83_dataliberacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh83_dataliberacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh83_dataliberacao_dia"] !="") ){ 
       $sql  .= $virgula." rh83_dataliberacao = '$this->rh83_dataliberacao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh83_dataliberacao_dia"])){ 
         $sql  .= $virgula." rh83_dataliberacao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh83_tipoempenho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh83_tipoempenho"])){ 
       $sql  .= $virgula." rh83_tipoempenho = $this->rh83_tipoempenho ";
       $virgula = ",";
       if(trim($this->rh83_tipoempenho) == null ){ 
         $this->erro_sql = " Campo Tipo de Empenho nao Informado.";
         $this->erro_campo = "rh83_tipoempenho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh83_tabprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh83_tabprev"])){ 
        if(trim($this->rh83_tabprev)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh83_tabprev"])){ 
           $this->rh83_tabprev = "0" ; 
        } 
       $sql  .= $virgula." rh83_tabprev = $this->rh83_tabprev ";
       $virgula = ",";
     }
     if(trim($this->rh83_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh83_instit"])){ 
       $sql  .= $virgula." rh83_instit = $this->rh83_instit ";
       $virgula = ",";
       if(trim($this->rh83_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "rh83_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh83_sequencial!=null){
       $sql .= " rh83_sequencial = $this->rh83_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh83_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14446,'$this->rh83_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh83_sequencial"]) || $this->rh83_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2548,14446,'".AddSlashes(pg_result($resaco,$conresaco,'rh83_sequencial'))."','$this->rh83_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh83_id_usuario"]) || $this->rh83_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2548,14447,'".AddSlashes(pg_result($resaco,$conresaco,'rh83_id_usuario'))."','$this->rh83_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh83_mesusu"]) || $this->rh83_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,2548,14449,'".AddSlashes(pg_result($resaco,$conresaco,'rh83_mesusu'))."','$this->rh83_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh83_anousu"]) || $this->rh83_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2548,14448,'".AddSlashes(pg_result($resaco,$conresaco,'rh83_anousu'))."','$this->rh83_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh83_siglaarq"]) || $this->rh83_siglaarq != "")
           $resac = db_query("insert into db_acount values($acount,2548,14450,'".AddSlashes(pg_result($resaco,$conresaco,'rh83_siglaarq'))."','$this->rh83_siglaarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh83_complementar"]) || $this->rh83_complementar != "")
           $resac = db_query("insert into db_acount values($acount,2548,14451,'".AddSlashes(pg_result($resaco,$conresaco,'rh83_complementar'))."','$this->rh83_complementar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh83_dataliberacao"]) || $this->rh83_dataliberacao != "")
           $resac = db_query("insert into db_acount values($acount,2548,14452,'".AddSlashes(pg_result($resaco,$conresaco,'rh83_dataliberacao'))."','$this->rh83_dataliberacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh83_tipoempenho"]) || $this->rh83_tipoempenho != "")
           $resac = db_query("insert into db_acount values($acount,2548,14460,'".AddSlashes(pg_result($resaco,$conresaco,'rh83_tipoempenho'))."','$this->rh83_tipoempenho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh83_tabprev"]) || $this->rh83_tabprev != "")
           $resac = db_query("insert into db_acount values($acount,2548,19215,'".AddSlashes(pg_result($resaco,$conresaco,'rh83_tabprev'))."','$this->rh83_tabprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh83_instit"]) || $this->rh83_instit != "")
           $resac = db_query("insert into db_acount values($acount,2548,15591,'".AddSlashes(pg_result($resaco,$conresaco,'rh83_instit'))."','$this->rh83_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liberação dos empenhos da folha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh83_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liberação dos empenhos da folha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh83_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh83_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh83_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh83_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14446,'$rh83_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2548,14446,'','".AddSlashes(pg_result($resaco,$iresaco,'rh83_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2548,14447,'','".AddSlashes(pg_result($resaco,$iresaco,'rh83_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2548,14449,'','".AddSlashes(pg_result($resaco,$iresaco,'rh83_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2548,14448,'','".AddSlashes(pg_result($resaco,$iresaco,'rh83_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2548,14450,'','".AddSlashes(pg_result($resaco,$iresaco,'rh83_siglaarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2548,14451,'','".AddSlashes(pg_result($resaco,$iresaco,'rh83_complementar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2548,14452,'','".AddSlashes(pg_result($resaco,$iresaco,'rh83_dataliberacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2548,14460,'','".AddSlashes(pg_result($resaco,$iresaco,'rh83_tipoempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2548,19215,'','".AddSlashes(pg_result($resaco,$iresaco,'rh83_tabprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2548,15591,'','".AddSlashes(pg_result($resaco,$iresaco,'rh83_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhempenhofolhaconfirma
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh83_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh83_sequencial = $rh83_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Liberação dos empenhos da folha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh83_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Liberação dos empenhos da folha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh83_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh83_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhempenhofolhaconfirma";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh83_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolhaconfirma ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rhempenhofolhaconfirma.rh83_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($rh83_sequencial!=null ){
         $sql2 .= " where rhempenhofolhaconfirma.rh83_sequencial = $rh83_sequencial "; 
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
   function sql_query_file ( $rh83_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhempenhofolhaconfirma ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh83_sequencial!=null ){
         $sql2 .= " where rhempenhofolhaconfirma.rh83_sequencial = $rh83_sequencial "; 
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
   * Retorna uma query para verificar se a folha slip esta liberada.
   * 
   * @access public
   * @param String $sCampo
   * @param String $sWhere
   * @param String $sOrder
   * @return String
   */
  public function sql_slip_liberada($sCampo = '*', $sWhere = '', $sOrder = '') {
    
    $sSql  = "SELECT {$sCampo}                                  "; 
    $sSql .= "  FROM rhempenhofolhaconfirma                     ";
    $sSql .= "    INNER JOIN rhslipfolha                        ";
    $sSql .= "      ON rh83_mesusu       = rh79_mesusu      AND ";   
    $sSql .= "         rh83_anousu       = rh79_anousu      AND ";
    $sSql .= "         rh83_siglaarq     = rh79_siglaarq    AND ";
    $sSql .= "         rh83_complementar = rh79_seqcompl    AND ";
    $sSql .= "         rh83_tipoempenho  = rh79_tipoempenho AND ";
    $sSql .= "         rh83_tabprev      = rh79_tabprev         ";
    
    if (!empty($sWhere)) {
      $sSql .= "  WHERE {$sWhere}                        ";
    }
    
    if (!empty($sOrder)) {
      $sSql .= "  ORDER BY {$sOrder}                     ";
    }
    
    return $sSql;
  }
  
}
?>