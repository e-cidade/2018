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
//CLASSE DA ENTIDADE rhregime
class cl_rhregime { 
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
   var $rh30_codreg = 0; 
   var $rh30_vinculomanad = 0; 
   var $rh30_descr = null; 
   var $rh30_regime = 0; 
   var $rh30_vinculo = null; 
   var $rh30_instit = 0; 
   var $rh30_naturezaregime = 0; 
   var $rh30_utilizacao = 0; 
   var $rh30_periodoaquisitivo = 0; 
   var $rh30_periodogozoferias = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh30_codreg = int4 = Código do Vínculo 
                 rh30_vinculomanad = int4 = Vínculo Manad 
                 rh30_descr = varchar(40) = Descrição 
                 rh30_regime = int4 = Regime 
                 rh30_vinculo = varchar(1) = Situação 
                 rh30_instit = int4 = Cod. Instituição 
                 rh30_naturezaregime = int4 = Codigo da Natureza 
                 rh30_utilizacao = int4 = Utilização 
                 rh30_periodoaquisitivo = int4 = Duração Período Aquisitivo 
                 rh30_periodogozoferias = int4 = Periodo de Gozo 
                 ";
   //funcao construtor da classe 
   function cl_rhregime() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhregime"); 
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
       $this->rh30_codreg = ($this->rh30_codreg == ""?@$GLOBALS["HTTP_POST_VARS"]["rh30_codreg"]:$this->rh30_codreg);
       $this->rh30_vinculomanad = ($this->rh30_vinculomanad == ""?@$GLOBALS["HTTP_POST_VARS"]["rh30_vinculomanad"]:$this->rh30_vinculomanad);
       $this->rh30_descr = ($this->rh30_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["rh30_descr"]:$this->rh30_descr);
       $this->rh30_regime = ($this->rh30_regime == ""?@$GLOBALS["HTTP_POST_VARS"]["rh30_regime"]:$this->rh30_regime);
       $this->rh30_vinculo = ($this->rh30_vinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh30_vinculo"]:$this->rh30_vinculo);
       $this->rh30_instit = ($this->rh30_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh30_instit"]:$this->rh30_instit);
       $this->rh30_naturezaregime = ($this->rh30_naturezaregime == ""?@$GLOBALS["HTTP_POST_VARS"]["rh30_naturezaregime"]:$this->rh30_naturezaregime);
       $this->rh30_utilizacao = ($this->rh30_utilizacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh30_utilizacao"]:$this->rh30_utilizacao);
       $this->rh30_periodoaquisitivo = ($this->rh30_periodoaquisitivo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh30_periodoaquisitivo"]:$this->rh30_periodoaquisitivo);
       $this->rh30_periodogozoferias = ($this->rh30_periodogozoferias == ""?@$GLOBALS["HTTP_POST_VARS"]["rh30_periodogozoferias"]:$this->rh30_periodogozoferias);
     }else{
       $this->rh30_codreg = ($this->rh30_codreg == ""?@$GLOBALS["HTTP_POST_VARS"]["rh30_codreg"]:$this->rh30_codreg);
     }
   }
   // funcao para inclusao
   function incluir ($rh30_codreg){ 
      $this->atualizacampos();
     if($this->rh30_vinculomanad == null ){ 
       $this->erro_sql = " Campo Vínculo Manad nao Informado.";
       $this->erro_campo = "rh30_vinculomanad";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh30_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "rh30_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh30_regime == null ){ 
       $this->erro_sql = " Campo Regime nao Informado.";
       $this->erro_campo = "rh30_regime";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh30_vinculo == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "rh30_vinculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh30_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "rh30_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh30_naturezaregime == null ){ 
       $this->erro_sql = " Campo Codigo da Natureza nao Informado.";
       $this->erro_campo = "rh30_naturezaregime";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh30_utilizacao == null ){ 
       $this->erro_sql = " Campo Utilização nao Informado.";
       $this->erro_campo = "rh30_utilizacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh30_periodoaquisitivo == null ){ 
       $this->erro_sql = " Campo Duração Período Aquisitivo nao Informado.";
       $this->erro_campo = "rh30_periodoaquisitivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh30_periodogozoferias == null ){ 
       $this->erro_sql = " Campo Periodo de Gozo nao Informado.";
       $this->erro_campo = "rh30_periodogozoferias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh30_codreg == "" || $rh30_codreg == null ){
       $result = db_query("select nextval('rhregime_rh30_codreg_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhregime_rh30_codreg_seq do campo: rh30_codreg"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh30_codreg = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhregime_rh30_codreg_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh30_codreg)){
         $this->erro_sql = " Campo rh30_codreg maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh30_codreg = $rh30_codreg; 
       }
     }
     if(($this->rh30_codreg == null) || ($this->rh30_codreg == "") ){ 
       $this->erro_sql = " Campo rh30_codreg nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhregime(
                                       rh30_codreg 
                                      ,rh30_vinculomanad 
                                      ,rh30_descr 
                                      ,rh30_regime 
                                      ,rh30_vinculo 
                                      ,rh30_instit 
                                      ,rh30_naturezaregime 
                                      ,rh30_utilizacao 
                                      ,rh30_periodoaquisitivo 
                                      ,rh30_periodogozoferias 
                       )
                values (
                                $this->rh30_codreg 
                               ,$this->rh30_vinculomanad 
                               ,'$this->rh30_descr' 
                               ,$this->rh30_regime 
                               ,'$this->rh30_vinculo' 
                               ,$this->rh30_instit 
                               ,$this->rh30_naturezaregime 
                               ,$this->rh30_utilizacao 
                               ,$this->rh30_periodoaquisitivo 
                               ,$this->rh30_periodogozoferias 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Regime dos funcionários ($this->rh30_codreg) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Regime dos funcionários já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Regime dos funcionários ($this->rh30_codreg) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh30_codreg;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh30_codreg  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7139,'$this->rh30_codreg','I')");
         $resac = db_query("insert into db_acount values($acount,1183,7139,'','".AddSlashes(pg_result($resaco,0,'rh30_codreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1183,14556,'','".AddSlashes(pg_result($resaco,0,'rh30_vinculomanad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1183,7140,'','".AddSlashes(pg_result($resaco,0,'rh30_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1183,7141,'','".AddSlashes(pg_result($resaco,0,'rh30_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1183,7142,'','".AddSlashes(pg_result($resaco,0,'rh30_vinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1183,9967,'','".AddSlashes(pg_result($resaco,0,'rh30_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1183,11892,'','".AddSlashes(pg_result($resaco,0,'rh30_naturezaregime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1183,17311,'','".AddSlashes(pg_result($resaco,0,'rh30_utilizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1183,19066,'','".AddSlashes(pg_result($resaco,0,'rh30_periodoaquisitivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1183,20163,'','".AddSlashes(pg_result($resaco,0,'rh30_periodogozoferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh30_codreg=null) { 
      $this->atualizacampos();
     $sql = " update rhregime set ";
     $virgula = "";
     if(trim($this->rh30_codreg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh30_codreg"])){ 
       $sql  .= $virgula." rh30_codreg = $this->rh30_codreg ";
       $virgula = ",";
       if(trim($this->rh30_codreg) == null ){ 
         $this->erro_sql = " Campo Código do Vínculo nao Informado.";
         $this->erro_campo = "rh30_codreg";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh30_vinculomanad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh30_vinculomanad"])){ 
       $sql  .= $virgula." rh30_vinculomanad = $this->rh30_vinculomanad ";
       $virgula = ",";
       if(trim($this->rh30_vinculomanad) == null ){ 
         $this->erro_sql = " Campo Vínculo Manad nao Informado.";
         $this->erro_campo = "rh30_vinculomanad";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh30_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh30_descr"])){ 
       $sql  .= $virgula." rh30_descr = '$this->rh30_descr' ";
       $virgula = ",";
       if(trim($this->rh30_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "rh30_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh30_regime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh30_regime"])){ 
       $sql  .= $virgula." rh30_regime = $this->rh30_regime ";
       $virgula = ",";
       if(trim($this->rh30_regime) == null ){ 
         $this->erro_sql = " Campo Regime nao Informado.";
         $this->erro_campo = "rh30_regime";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh30_vinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh30_vinculo"])){ 
       $sql  .= $virgula." rh30_vinculo = '$this->rh30_vinculo' ";
       $virgula = ",";
       if(trim($this->rh30_vinculo) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "rh30_vinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh30_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh30_instit"])){ 
       $sql  .= $virgula." rh30_instit = $this->rh30_instit ";
       $virgula = ",";
       if(trim($this->rh30_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "rh30_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh30_naturezaregime)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh30_naturezaregime"])){ 
       $sql  .= $virgula." rh30_naturezaregime = $this->rh30_naturezaregime ";
       $virgula = ",";
       if(trim($this->rh30_naturezaregime) == null ){ 
         $this->erro_sql = " Campo Codigo da Natureza nao Informado.";
         $this->erro_campo = "rh30_naturezaregime";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh30_utilizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh30_utilizacao"])){ 
       $sql  .= $virgula." rh30_utilizacao = $this->rh30_utilizacao ";
       $virgula = ",";
       if(trim($this->rh30_utilizacao) == null ){ 
         $this->erro_sql = " Campo Utilização nao Informado.";
         $this->erro_campo = "rh30_utilizacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh30_periodoaquisitivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh30_periodoaquisitivo"])){ 
       $sql  .= $virgula." rh30_periodoaquisitivo = $this->rh30_periodoaquisitivo ";
       $virgula = ",";
       if(trim($this->rh30_periodoaquisitivo) == null ){ 
         $this->erro_sql = " Campo Duração Período Aquisitivo nao Informado.";
         $this->erro_campo = "rh30_periodoaquisitivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh30_periodogozoferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh30_periodogozoferias"])){ 
       $sql  .= $virgula." rh30_periodogozoferias = $this->rh30_periodogozoferias ";
       $virgula = ",";
       if(trim($this->rh30_periodogozoferias) == null ){ 
         $this->erro_sql = " Campo Periodo de Gozo nao Informado.";
         $this->erro_campo = "rh30_periodogozoferias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh30_codreg!=null){
       $sql .= " rh30_codreg = $this->rh30_codreg";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh30_codreg));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,7139,'$this->rh30_codreg','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh30_codreg"]) || $this->rh30_codreg != "")
             $resac = db_query("insert into db_acount values($acount,1183,7139,'".AddSlashes(pg_result($resaco,$conresaco,'rh30_codreg'))."','$this->rh30_codreg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh30_vinculomanad"]) || $this->rh30_vinculomanad != "")
             $resac = db_query("insert into db_acount values($acount,1183,14556,'".AddSlashes(pg_result($resaco,$conresaco,'rh30_vinculomanad'))."','$this->rh30_vinculomanad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh30_descr"]) || $this->rh30_descr != "")
             $resac = db_query("insert into db_acount values($acount,1183,7140,'".AddSlashes(pg_result($resaco,$conresaco,'rh30_descr'))."','$this->rh30_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh30_regime"]) || $this->rh30_regime != "")
             $resac = db_query("insert into db_acount values($acount,1183,7141,'".AddSlashes(pg_result($resaco,$conresaco,'rh30_regime'))."','$this->rh30_regime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh30_vinculo"]) || $this->rh30_vinculo != "")
             $resac = db_query("insert into db_acount values($acount,1183,7142,'".AddSlashes(pg_result($resaco,$conresaco,'rh30_vinculo'))."','$this->rh30_vinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh30_instit"]) || $this->rh30_instit != "")
             $resac = db_query("insert into db_acount values($acount,1183,9967,'".AddSlashes(pg_result($resaco,$conresaco,'rh30_instit'))."','$this->rh30_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh30_naturezaregime"]) || $this->rh30_naturezaregime != "")
             $resac = db_query("insert into db_acount values($acount,1183,11892,'".AddSlashes(pg_result($resaco,$conresaco,'rh30_naturezaregime'))."','$this->rh30_naturezaregime',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh30_utilizacao"]) || $this->rh30_utilizacao != "")
             $resac = db_query("insert into db_acount values($acount,1183,17311,'".AddSlashes(pg_result($resaco,$conresaco,'rh30_utilizacao'))."','$this->rh30_utilizacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh30_periodoaquisitivo"]) || $this->rh30_periodoaquisitivo != "")
             $resac = db_query("insert into db_acount values($acount,1183,19066,'".AddSlashes(pg_result($resaco,$conresaco,'rh30_periodoaquisitivo'))."','$this->rh30_periodoaquisitivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh30_periodogozoferias"]) || $this->rh30_periodogozoferias != "")
             $resac = db_query("insert into db_acount values($acount,1183,20163,'".AddSlashes(pg_result($resaco,$conresaco,'rh30_periodogozoferias'))."','$this->rh30_periodogozoferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regime dos funcionários nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh30_codreg;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Regime dos funcionários nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh30_codreg;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh30_codreg;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh30_codreg=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh30_codreg));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,7139,'$rh30_codreg','E')");
           $resac  = db_query("insert into db_acount values($acount,1183,7139,'','".AddSlashes(pg_result($resaco,$iresaco,'rh30_codreg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1183,14556,'','".AddSlashes(pg_result($resaco,$iresaco,'rh30_vinculomanad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1183,7140,'','".AddSlashes(pg_result($resaco,$iresaco,'rh30_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1183,7141,'','".AddSlashes(pg_result($resaco,$iresaco,'rh30_regime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1183,7142,'','".AddSlashes(pg_result($resaco,$iresaco,'rh30_vinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1183,9967,'','".AddSlashes(pg_result($resaco,$iresaco,'rh30_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1183,11892,'','".AddSlashes(pg_result($resaco,$iresaco,'rh30_naturezaregime'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1183,17311,'','".AddSlashes(pg_result($resaco,$iresaco,'rh30_utilizacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1183,19066,'','".AddSlashes(pg_result($resaco,$iresaco,'rh30_periodoaquisitivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1183,20163,'','".AddSlashes(pg_result($resaco,$iresaco,'rh30_periodogozoferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhregime
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh30_codreg != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh30_codreg = $rh30_codreg ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regime dos funcionários nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh30_codreg;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Regime dos funcionários nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh30_codreg;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh30_codreg;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhregime";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh30_codreg=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhregime ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhregime.rh30_instit";
     $sql .= "      inner join rhcadregime  on  rhcadregime.rh52_regime = rhregime.rh30_regime";
     $sql .= "      inner join rhnaturezaregime  on  rhnaturezaregime.rh71_sequencial = rhregime.rh30_naturezaregime";
     $sql .= "      inner join vinculomanad  on  vinculomanad.rh84_sequencial = rhregime.rh30_vinculomanad";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($rh30_codreg!=null ){
         $sql2 .= " where rhregime.rh30_codreg = $rh30_codreg "; 
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
   function sql_query_file ( $rh30_codreg=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhregime ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh30_codreg!=null ){
         $sql2 .= " where rhregime.rh30_codreg = $rh30_codreg "; 
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
   function sql_query_rescisao ( $rh30_codreg=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from rhregime ";
     $sql .= "      inner join rescisao on r59_anousu = ".db_anofolha()."
                                       and r59_mesusu = ".db_mesfolha()."
                                       and r59_regime = rh30_regime ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh30_codreg!=null ){
         $sql2 .= " where rhregime.rh30_codreg = $rh30_codreg
                      and rhregime.rh30_instit = ".db_getsession("DB_instit");
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere and rhregime.rh30_instit = ".db_getsession("DB_instit")." ";
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
	 * Query de retorno dos servidores por tipo de vinculo no exercicio e ano da folha informados
	 * @param integer $iAnoUsu Ano da folha
	 * @param integer $iMesUsu Mês da folha
	 * @param integer $iVinculoServidor Código do vínculo
	 * @param string $sCampos Campos das tabelas envolvidas
	 * @param string $iInstituicao Instituicao do servidor
	 * @param string $sWhere 
	 * @return string
	 */
	function sql_query_servidorerPorVinculo($iAnoUsu, $iMesUsu, $iVinculoServidor, $sCampos = null, $sInstituicao = null, $sWhere = null) {
		
		if ( empty($sCampos) ) {
			$sCampos = "*";
		}
	
		if ( empty($sInstituicao) ) {
			$sInstituicao = db_getsession('DB_instit');
		}
	
		$sSql  = "select {$sCampos}                                            ";
		$sSql .= "  from rhpessoalmov                                          ";
		$sSql .= "       inner join rhpessoal     on rh01_regist = rh02_regist ";
		$sSql .= "       inner join rhregime      on rh30_codreg = rh02_codreg ";
		$sSql .= "        left join rhpesrescisao on rh05_seqpes = rh02_seqpes ";
		
		/**
		 * Inativos 
		 */
		if ($iVinculoServidor > 0) {
			$sSql .= "    inner join rhtipoapos on rhtipoapos.rh88_sequencial = rhpessoalmov.rh02_rhtipoapos \n";
		}
		
		$sSql .= " where rh02_anousu = {$iAnoUsu}               ";
		$sSql .= "   and rh02_mesusu = {$iMesUsu}               ";
		$sSql .= "   and rh02_instit in ({$sInstituicao})       ";
		
		if ($iVinculoServidor == 0) {			
			$sSql .= " and rh30_vinculo    = 'A'                  ";
		}  else {		                                            
			$sSql .= " and rh30_vinculo    in ('P', 'I')          ";
			$sSql .= " and rh88_sequencial = {$iVinculoServidor}  ";
		}
		
		if ( !empty($sWhere) ) {
			$sSql .= " and {$sWhere} 														  ";
		}
	
		$sSql .= "   and rh05_seqpes is null                    ";
		
		$sSql .= "order by {$sCampos}";
		
		return $sSql;	
	}
   function sql_query_servidores($iAnoUsu, $iMesUsu, $iTipoRegime, $sCampos=null , $iInstituicao = null) {

    if ( empty($sCampos) ) {
   		$sCampos = "*";
   	}

   	if ( empty($iInstituicao)) {
   		$iInstituicao = db_getsession('DB_instit');
   	}

   	$sSql = "select {$sCampos}                                         \n";
   	$sSql.= "  from rhpessoalmov                                       \n";
   	$sSql.= "       inner join rhregime on rh30_codreg = rh02_codreg   \n";
   	$sSql.= " where rh02_anousu = $iAnoUsu                             \n";
   	$sSql.= "   and rh02_mesusu = $iMesUsu                             \n";
   	$sSql.= "   and rh02_instit = $iInstituicao                        \n";
   	$sSql.= "   and rh30_regime = $iTipoRegime                         \n";
   	
   	return $sSql;
   	
	}
}
?>