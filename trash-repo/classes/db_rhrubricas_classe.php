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
//CLASSE DA ENTIDADE rhrubricas
class cl_rhrubricas { 
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
   var $rh27_rubric = null; 
   var $rh27_descr = null; 
   var $rh27_pd = 0; 
   var $rh27_quant = 0; 
   var $rh27_cond2 = null; 
   var $rh27_cond3 = null; 
   var $rh27_form = null; 
   var $rh27_form2 = null; 
   var $rh27_form3 = null; 
   var $rh27_formq = null; 
   var $rh27_calc1 = 0; 
   var $rh27_calc2 = 0; 
   var $rh27_calc3 = 'f'; 
   var $rh27_tipo = 0; 
   var $rh27_limdat = 'f'; 
   var $rh27_presta = 'f'; 
   var $rh27_calcp = 'f'; 
   var $rh27_propq = 'f'; 
   var $rh27_propi = 'f'; 
   var $rh27_obs = null; 
   var $rh27_instit = 0; 
   var $rh27_ativo = 'f'; 
   var $rh27_complementarautomatica = 'f'; 
   var $rh27_valorpadrao = 0; 
   var $rh27_quantidadepadrao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh27_rubric = char(4) = Código da Rubrica 
                 rh27_descr = varchar(50) = Descrição do Código 
                 rh27_pd = int4 = Tipo Rubrica 
                 rh27_quant = float8 = Qtda ou Valor para Inicializar 
                 rh27_cond2 = varchar(120) = Condição da Fórmula 2 
                 rh27_cond3 = varchar(120) = Condição da Fórmula 3 
                 rh27_form = varchar(120) = Fórmula 
                 rh27_form2 = varchar(120) = Fórmula 2 
                 rh27_form3 = varchar(120) = Fórmula  3 
                 rh27_formq = varchar(120) = Fórmula Quantidade 
                 rh27_calc1 = int4 = Média p/ Férias 
                 rh27_calc2 = int4 = Média p/ 13º  Salário 
                 rh27_calc3 = bool = Código Rescisão 
                 rh27_tipo = int4 = Tipo de Inicialização 
                 rh27_limdat = bool = Usa Data Limite 
                 rh27_presta = bool = Calcula Prestações 
                 rh27_calcp = bool = Proporcionaliza no Afastamento 
                 rh27_propq = bool = Proporcionaliza nas Médias 
                 rh27_propi = bool = Proporção para Inativos 
                 rh27_obs = varchar(120) = Observação 
                 rh27_instit = int4 = codigo da instituicao 
                 rh27_ativo = bool = Ativo 
                 rh27_complementarautomatica = bool = Auto. Complementar 
                 rh27_valorpadrao = float8 = Valor Padrão 
                 rh27_quantidadepadrao = float8 = Quantidade Padrão 
                 ";
   //funcao construtor da classe 
   function cl_rhrubricas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhrubricas"); 
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
       $this->rh27_rubric = ($this->rh27_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_rubric"]:$this->rh27_rubric);
       $this->rh27_descr = ($this->rh27_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_descr"]:$this->rh27_descr);
       $this->rh27_pd = ($this->rh27_pd == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_pd"]:$this->rh27_pd);
       $this->rh27_quant = ($this->rh27_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_quant"]:$this->rh27_quant);
       $this->rh27_cond2 = ($this->rh27_cond2 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_cond2"]:$this->rh27_cond2);
       $this->rh27_cond3 = ($this->rh27_cond3 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_cond3"]:$this->rh27_cond3);
       $this->rh27_form = ($this->rh27_form == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_form"]:$this->rh27_form);
       $this->rh27_form2 = ($this->rh27_form2 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_form2"]:$this->rh27_form2);
       $this->rh27_form3 = ($this->rh27_form3 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_form3"]:$this->rh27_form3);
       $this->rh27_formq = ($this->rh27_formq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_formq"]:$this->rh27_formq);
       $this->rh27_calc1 = ($this->rh27_calc1 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_calc1"]:$this->rh27_calc1);
       $this->rh27_calc2 = ($this->rh27_calc2 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_calc2"]:$this->rh27_calc2);
       $this->rh27_calc3 = ($this->rh27_calc3 == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh27_calc3"]:$this->rh27_calc3);
       $this->rh27_tipo = ($this->rh27_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_tipo"]:$this->rh27_tipo);
       $this->rh27_limdat = ($this->rh27_limdat == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh27_limdat"]:$this->rh27_limdat);
       $this->rh27_presta = ($this->rh27_presta == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh27_presta"]:$this->rh27_presta);
       $this->rh27_calcp = ($this->rh27_calcp == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh27_calcp"]:$this->rh27_calcp);
       $this->rh27_propq = ($this->rh27_propq == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh27_propq"]:$this->rh27_propq);
       $this->rh27_propi = ($this->rh27_propi == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh27_propi"]:$this->rh27_propi);
       $this->rh27_obs = ($this->rh27_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_obs"]:$this->rh27_obs);
       $this->rh27_instit = ($this->rh27_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_instit"]:$this->rh27_instit);
       $this->rh27_ativo = ($this->rh27_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh27_ativo"]:$this->rh27_ativo);
       $this->rh27_complementarautomatica = ($this->rh27_complementarautomatica == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh27_complementarautomatica"]:$this->rh27_complementarautomatica);
       $this->rh27_valorpadrao = ($this->rh27_valorpadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_valorpadrao"]:$this->rh27_valorpadrao);
       $this->rh27_quantidadepadrao = ($this->rh27_quantidadepadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_quantidadepadrao"]:$this->rh27_quantidadepadrao);
     }else{
       $this->rh27_rubric = ($this->rh27_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_rubric"]:$this->rh27_rubric);
       $this->rh27_instit = ($this->rh27_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh27_instit"]:$this->rh27_instit);
     }
   }
   // funcao para inclusao
   function incluir ($rh27_rubric,$rh27_instit){ 
      $this->atualizacampos();
     if($this->rh27_descr == null ){ 
       $this->erro_sql = " Campo Descrição do Código nao Informado.";
       $this->erro_campo = "rh27_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_pd == null ){ 
       $this->erro_sql = " Campo Tipo Rubrica nao Informado.";
       $this->erro_campo = "rh27_pd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_quant == null ){ 
       $this->erro_sql = " Campo Qtda ou Valor para Inicializar nao Informado.";
       $this->erro_campo = "rh27_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_calc1 == null ){ 
       $this->erro_sql = " Campo Média p/ Férias nao Informado.";
       $this->erro_campo = "rh27_calc1";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_calc2 == null ){ 
       $this->erro_sql = " Campo Média p/ 13º  Salário nao Informado.";
       $this->erro_campo = "rh27_calc2";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_calc3 == null ){ 
       $this->erro_sql = " Campo Código Rescisão nao Informado.";
       $this->erro_campo = "rh27_calc3";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Inicialização nao Informado.";
       $this->erro_campo = "rh27_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_limdat == null ){ 
       $this->erro_sql = " Campo Usa Data Limite nao Informado.";
       $this->erro_campo = "rh27_limdat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_presta == null ){ 
       $this->erro_sql = " Campo Calcula Prestações nao Informado.";
       $this->erro_campo = "rh27_presta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_calcp == null ){ 
       $this->erro_sql = " Campo Proporcionaliza no Afastamento nao Informado.";
       $this->erro_campo = "rh27_calcp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_propq == null ){ 
       $this->erro_sql = " Campo Proporcionaliza nas Médias nao Informado.";
       $this->erro_campo = "rh27_propq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_propi == null ){ 
       $this->erro_sql = " Campo Proporção para Inativos nao Informado.";
       $this->erro_campo = "rh27_propi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "rh27_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_complementarautomatica == null ){ 
       $this->erro_sql = " Campo Auto. Complementar nao Informado.";
       $this->erro_campo = "rh27_complementarautomatica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh27_valorpadrao == null ){ 
       $this->rh27_valorpadrao = "0";
     }
     if($this->rh27_quantidadepadrao == null ){ 
       $this->rh27_quantidadepadrao = "0";
     }
       $this->rh27_rubric = $rh27_rubric; 
       $this->rh27_instit = $rh27_instit; 
     if(($this->rh27_rubric == null) || ($this->rh27_rubric == "") ){ 
       $this->erro_sql = " Campo rh27_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh27_instit == null) || ($this->rh27_instit == "") ){ 
       $this->erro_sql = " Campo rh27_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhrubricas(
                                       rh27_rubric 
                                      ,rh27_descr 
                                      ,rh27_pd 
                                      ,rh27_quant 
                                      ,rh27_cond2 
                                      ,rh27_cond3 
                                      ,rh27_form 
                                      ,rh27_form2 
                                      ,rh27_form3 
                                      ,rh27_formq 
                                      ,rh27_calc1 
                                      ,rh27_calc2 
                                      ,rh27_calc3 
                                      ,rh27_tipo 
                                      ,rh27_limdat 
                                      ,rh27_presta 
                                      ,rh27_calcp 
                                      ,rh27_propq 
                                      ,rh27_propi 
                                      ,rh27_obs 
                                      ,rh27_instit 
                                      ,rh27_ativo 
                                      ,rh27_complementarautomatica 
                                      ,rh27_valorpadrao 
                                      ,rh27_quantidadepadrao 
                       )
                values (
                                '$this->rh27_rubric' 
                               ,'$this->rh27_descr' 
                               ,$this->rh27_pd 
                               ,$this->rh27_quant 
                               ,'$this->rh27_cond2' 
                               ,'$this->rh27_cond3' 
                               ,'$this->rh27_form' 
                               ,'$this->rh27_form2' 
                               ,'$this->rh27_form3' 
                               ,'$this->rh27_formq' 
                               ,$this->rh27_calc1 
                               ,$this->rh27_calc2 
                               ,'$this->rh27_calc3' 
                               ,$this->rh27_tipo 
                               ,'$this->rh27_limdat' 
                               ,'$this->rh27_presta' 
                               ,'$this->rh27_calcp' 
                               ,'$this->rh27_propq' 
                               ,'$this->rh27_propi' 
                               ,'$this->rh27_obs' 
                               ,$this->rh27_instit 
                               ,'$this->rh27_ativo' 
                               ,'$this->rh27_complementarautomatica' 
                               ,$this->rh27_valorpadrao 
                               ,$this->rh27_quantidadepadrao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de rubricas ($this->rh27_rubric."-".$this->rh27_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de rubricas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de rubricas ($this->rh27_rubric."-".$this->rh27_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh27_rubric."-".$this->rh27_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh27_rubric,$this->rh27_instit  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7104,'$this->rh27_rubric','I')");
         $resac = db_query("insert into db_acountkey values($acount,7472,'$this->rh27_instit','I')");
         $resac = db_query("insert into db_acount values($acount,1177,7104,'','".AddSlashes(pg_result($resaco,0,'rh27_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7105,'','".AddSlashes(pg_result($resaco,0,'rh27_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7106,'','".AddSlashes(pg_result($resaco,0,'rh27_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7108,'','".AddSlashes(pg_result($resaco,0,'rh27_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7115,'','".AddSlashes(pg_result($resaco,0,'rh27_cond2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7116,'','".AddSlashes(pg_result($resaco,0,'rh27_cond3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7107,'','".AddSlashes(pg_result($resaco,0,'rh27_form'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7117,'','".AddSlashes(pg_result($resaco,0,'rh27_form2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7118,'','".AddSlashes(pg_result($resaco,0,'rh27_form3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7122,'','".AddSlashes(pg_result($resaco,0,'rh27_formq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7109,'','".AddSlashes(pg_result($resaco,0,'rh27_calc1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7110,'','".AddSlashes(pg_result($resaco,0,'rh27_calc2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7111,'','".AddSlashes(pg_result($resaco,0,'rh27_calc3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7112,'','".AddSlashes(pg_result($resaco,0,'rh27_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7113,'','".AddSlashes(pg_result($resaco,0,'rh27_limdat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7121,'','".AddSlashes(pg_result($resaco,0,'rh27_presta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7119,'','".AddSlashes(pg_result($resaco,0,'rh27_calcp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7120,'','".AddSlashes(pg_result($resaco,0,'rh27_propq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7123,'','".AddSlashes(pg_result($resaco,0,'rh27_propi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7114,'','".AddSlashes(pg_result($resaco,0,'rh27_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,7472,'','".AddSlashes(pg_result($resaco,0,'rh27_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,11886,'','".AddSlashes(pg_result($resaco,0,'rh27_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,20048,'','".AddSlashes(pg_result($resaco,0,'rh27_complementarautomatica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,20068,'','".AddSlashes(pg_result($resaco,0,'rh27_valorpadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1177,20069,'','".AddSlashes(pg_result($resaco,0,'rh27_quantidadepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh27_rubric=null,$rh27_instit=null) { 
      $this->atualizacampos();
     $sql = " update rhrubricas set ";
     $virgula = "";
     if(trim($this->rh27_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_rubric"])){ 
       $sql  .= $virgula." rh27_rubric = '$this->rh27_rubric' ";
       $virgula = ",";
       if(trim($this->rh27_rubric) == null ){ 
         $this->erro_sql = " Campo Código da Rubrica nao Informado.";
         $this->erro_campo = "rh27_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_descr"])){ 
       $sql  .= $virgula." rh27_descr = '$this->rh27_descr' ";
       $virgula = ",";
       if(trim($this->rh27_descr) == null ){ 
         $this->erro_sql = " Campo Descrição do Código nao Informado.";
         $this->erro_campo = "rh27_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_pd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_pd"])){ 
       $sql  .= $virgula." rh27_pd = $this->rh27_pd ";
       $virgula = ",";
       if(trim($this->rh27_pd) == null ){ 
         $this->erro_sql = " Campo Tipo Rubrica nao Informado.";
         $this->erro_campo = "rh27_pd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_quant"])){ 
       $sql  .= $virgula." rh27_quant = $this->rh27_quant ";
       $virgula = ",";
       if(trim($this->rh27_quant) == null ){ 
         $this->erro_sql = " Campo Qtda ou Valor para Inicializar nao Informado.";
         $this->erro_campo = "rh27_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_cond2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_cond2"])){ 
       $sql  .= $virgula." rh27_cond2 = '$this->rh27_cond2' ";
       $virgula = ",";
     }
     if(trim($this->rh27_cond3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_cond3"])){ 
       $sql  .= $virgula." rh27_cond3 = '$this->rh27_cond3' ";
       $virgula = ",";
     }
     if(trim($this->rh27_form)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_form"])){ 
       $sql  .= $virgula." rh27_form = '$this->rh27_form' ";
       $virgula = ",";
     }
     if(trim($this->rh27_form2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_form2"])){ 
       $sql  .= $virgula." rh27_form2 = '$this->rh27_form2' ";
       $virgula = ",";
     }
     if(trim($this->rh27_form3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_form3"])){ 
       $sql  .= $virgula." rh27_form3 = '$this->rh27_form3' ";
       $virgula = ",";
     }
     if(trim($this->rh27_formq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_formq"])){ 
       $sql  .= $virgula." rh27_formq = '$this->rh27_formq' ";
       $virgula = ",";
     }
     if(trim($this->rh27_calc1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_calc1"])){ 
       $sql  .= $virgula." rh27_calc1 = $this->rh27_calc1 ";
       $virgula = ",";
       if(trim($this->rh27_calc1) == null ){ 
         $this->erro_sql = " Campo Média p/ Férias nao Informado.";
         $this->erro_campo = "rh27_calc1";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_calc2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_calc2"])){ 
       $sql  .= $virgula." rh27_calc2 = $this->rh27_calc2 ";
       $virgula = ",";
       if(trim($this->rh27_calc2) == null ){ 
         $this->erro_sql = " Campo Média p/ 13º  Salário nao Informado.";
         $this->erro_campo = "rh27_calc2";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_calc3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_calc3"])){ 
       $sql  .= $virgula." rh27_calc3 = '$this->rh27_calc3' ";
       $virgula = ",";
       if(trim($this->rh27_calc3) == null ){ 
         $this->erro_sql = " Campo Código Rescisão nao Informado.";
         $this->erro_campo = "rh27_calc3";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_tipo"])){ 
       $sql  .= $virgula." rh27_tipo = $this->rh27_tipo ";
       $virgula = ",";
       if(trim($this->rh27_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Inicialização nao Informado.";
         $this->erro_campo = "rh27_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_limdat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_limdat"])){ 
       $sql  .= $virgula." rh27_limdat = '$this->rh27_limdat' ";
       $virgula = ",";
       if(trim($this->rh27_limdat) == null ){ 
         $this->erro_sql = " Campo Usa Data Limite nao Informado.";
         $this->erro_campo = "rh27_limdat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_presta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_presta"])){ 
       $sql  .= $virgula." rh27_presta = '$this->rh27_presta' ";
       $virgula = ",";
       if(trim($this->rh27_presta) == null ){ 
         $this->erro_sql = " Campo Calcula Prestações nao Informado.";
         $this->erro_campo = "rh27_presta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_calcp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_calcp"])){ 
       $sql  .= $virgula." rh27_calcp = '$this->rh27_calcp' ";
       $virgula = ",";
       if(trim($this->rh27_calcp) == null ){ 
         $this->erro_sql = " Campo Proporcionaliza no Afastamento nao Informado.";
         $this->erro_campo = "rh27_calcp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_propq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_propq"])){ 
       $sql  .= $virgula." rh27_propq = '$this->rh27_propq' ";
       $virgula = ",";
       if(trim($this->rh27_propq) == null ){ 
         $this->erro_sql = " Campo Proporcionaliza nas Médias nao Informado.";
         $this->erro_campo = "rh27_propq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_propi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_propi"])){ 
       $sql  .= $virgula." rh27_propi = '$this->rh27_propi' ";
       $virgula = ",";
       if(trim($this->rh27_propi) == null ){ 
         $this->erro_sql = " Campo Proporção para Inativos nao Informado.";
         $this->erro_campo = "rh27_propi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_obs"])){ 
       $sql  .= $virgula." rh27_obs = '$this->rh27_obs' ";
       $virgula = ",";
     }
     if(trim($this->rh27_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_instit"])){ 
       $sql  .= $virgula." rh27_instit = $this->rh27_instit ";
       $virgula = ",";
       if(trim($this->rh27_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "rh27_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_ativo"])){ 
       $sql  .= $virgula." rh27_ativo = '$this->rh27_ativo' ";
       $virgula = ",";
       if(trim($this->rh27_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "rh27_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_complementarautomatica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_complementarautomatica"])){ 
       $sql  .= $virgula." rh27_complementarautomatica = '$this->rh27_complementarautomatica' ";
       $virgula = ",";
       if(trim($this->rh27_complementarautomatica) == null ){ 
         $this->erro_sql = " Campo Auto. Complementar nao Informado.";
         $this->erro_campo = "rh27_complementarautomatica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh27_valorpadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_valorpadrao"])){ 
        if(trim($this->rh27_valorpadrao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh27_valorpadrao"])){ 
           $this->rh27_valorpadrao = "0" ; 
        } 
       $sql  .= $virgula." rh27_valorpadrao = $this->rh27_valorpadrao ";
       $virgula = ",";
     }
     if(trim($this->rh27_quantidadepadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh27_quantidadepadrao"])){ 
        if(trim($this->rh27_quantidadepadrao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh27_quantidadepadrao"])){ 
           $this->rh27_quantidadepadrao = "0" ; 
        } 
       $sql  .= $virgula." rh27_quantidadepadrao = $this->rh27_quantidadepadrao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh27_rubric!=null){
       $sql .= " rh27_rubric = '$this->rh27_rubric'";
     }
     if($rh27_instit!=null){
       $sql .= " and  rh27_instit = $this->rh27_instit";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh27_rubric,$this->rh27_instit));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,7104,'$this->rh27_rubric','A')");
           $resac = db_query("insert into db_acountkey values($acount,7472,'$this->rh27_instit','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_rubric"]) || $this->rh27_rubric != "")
             $resac = db_query("insert into db_acount values($acount,1177,7104,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_rubric'))."','$this->rh27_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_descr"]) || $this->rh27_descr != "")
             $resac = db_query("insert into db_acount values($acount,1177,7105,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_descr'))."','$this->rh27_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_pd"]) || $this->rh27_pd != "")
             $resac = db_query("insert into db_acount values($acount,1177,7106,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_pd'))."','$this->rh27_pd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_quant"]) || $this->rh27_quant != "")
             $resac = db_query("insert into db_acount values($acount,1177,7108,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_quant'))."','$this->rh27_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_cond2"]) || $this->rh27_cond2 != "")
             $resac = db_query("insert into db_acount values($acount,1177,7115,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_cond2'))."','$this->rh27_cond2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_cond3"]) || $this->rh27_cond3 != "")
             $resac = db_query("insert into db_acount values($acount,1177,7116,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_cond3'))."','$this->rh27_cond3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_form"]) || $this->rh27_form != "")
             $resac = db_query("insert into db_acount values($acount,1177,7107,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_form'))."','$this->rh27_form',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_form2"]) || $this->rh27_form2 != "")
             $resac = db_query("insert into db_acount values($acount,1177,7117,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_form2'))."','$this->rh27_form2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_form3"]) || $this->rh27_form3 != "")
             $resac = db_query("insert into db_acount values($acount,1177,7118,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_form3'))."','$this->rh27_form3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_formq"]) || $this->rh27_formq != "")
             $resac = db_query("insert into db_acount values($acount,1177,7122,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_formq'))."','$this->rh27_formq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_calc1"]) || $this->rh27_calc1 != "")
             $resac = db_query("insert into db_acount values($acount,1177,7109,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_calc1'))."','$this->rh27_calc1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_calc2"]) || $this->rh27_calc2 != "")
             $resac = db_query("insert into db_acount values($acount,1177,7110,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_calc2'))."','$this->rh27_calc2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_calc3"]) || $this->rh27_calc3 != "")
             $resac = db_query("insert into db_acount values($acount,1177,7111,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_calc3'))."','$this->rh27_calc3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_tipo"]) || $this->rh27_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1177,7112,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_tipo'))."','$this->rh27_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_limdat"]) || $this->rh27_limdat != "")
             $resac = db_query("insert into db_acount values($acount,1177,7113,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_limdat'))."','$this->rh27_limdat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_presta"]) || $this->rh27_presta != "")
             $resac = db_query("insert into db_acount values($acount,1177,7121,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_presta'))."','$this->rh27_presta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_calcp"]) || $this->rh27_calcp != "")
             $resac = db_query("insert into db_acount values($acount,1177,7119,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_calcp'))."','$this->rh27_calcp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_propq"]) || $this->rh27_propq != "")
             $resac = db_query("insert into db_acount values($acount,1177,7120,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_propq'))."','$this->rh27_propq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_propi"]) || $this->rh27_propi != "")
             $resac = db_query("insert into db_acount values($acount,1177,7123,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_propi'))."','$this->rh27_propi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_obs"]) || $this->rh27_obs != "")
             $resac = db_query("insert into db_acount values($acount,1177,7114,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_obs'))."','$this->rh27_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_instit"]) || $this->rh27_instit != "")
             $resac = db_query("insert into db_acount values($acount,1177,7472,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_instit'))."','$this->rh27_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_ativo"]) || $this->rh27_ativo != "")
             $resac = db_query("insert into db_acount values($acount,1177,11886,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_ativo'))."','$this->rh27_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_complementarautomatica"]) || $this->rh27_complementarautomatica != "")
             $resac = db_query("insert into db_acount values($acount,1177,20048,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_complementarautomatica'))."','$this->rh27_complementarautomatica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_valorpadrao"]) || $this->rh27_valorpadrao != "")
             $resac = db_query("insert into db_acount values($acount,1177,20068,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_valorpadrao'))."','$this->rh27_valorpadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh27_quantidadepadrao"]) || $this->rh27_quantidadepadrao != "")
             $resac = db_query("insert into db_acount values($acount,1177,20069,'".AddSlashes(pg_result($resaco,$conresaco,'rh27_quantidadepadrao'))."','$this->rh27_quantidadepadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de rubricas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh27_rubric."-".$this->rh27_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de rubricas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh27_rubric."-".$this->rh27_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh27_rubric."-".$this->rh27_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh27_rubric=null,$rh27_instit=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh27_rubric,$rh27_instit));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,7104,'$rh27_rubric','E')");
           $resac  = db_query("insert into db_acountkey values($acount,7472,'$rh27_instit','E')");
           $resac  = db_query("insert into db_acount values($acount,1177,7104,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7105,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7106,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7108,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7115,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_cond2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7116,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_cond3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7107,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_form'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7117,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_form2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7118,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_form3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7122,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_formq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7109,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_calc1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7110,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_calc2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7111,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_calc3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7112,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7113,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_limdat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7121,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_presta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7119,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_calcp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7120,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_propq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7123,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_propi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7114,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,7472,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,11886,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,20048,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_complementarautomatica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,20068,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_valorpadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1177,20069,'','".AddSlashes(pg_result($resaco,$iresaco,'rh27_quantidadepadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhrubricas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh27_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh27_rubric = '$rh27_rubric' ";
        }
        if($rh27_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh27_instit = $rh27_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de rubricas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh27_rubric."-".$rh27_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de rubricas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh27_rubric."-".$rh27_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh27_rubric."-".$rh27_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhrubricas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh27_rubric=null,$rh27_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhrubricas ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhrubricas.rh27_instit";
     $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
     $sql .= "      inner join rhtipomedia b on  b.rh29_tipo = rhrubricas.rh27_calc2";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($rh27_rubric!=null ){
         $sql2 .= " where rhrubricas.rh27_rubric = '$rh27_rubric' "; 
       } 
       if($rh27_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhrubricas.rh27_instit = $rh27_instit "; 
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
   function sql_query_file ( $rh27_rubric=null,$rh27_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhrubricas ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh27_rubric!=null ){
         $sql2 .= " where rhrubricas.rh27_rubric = '$rh27_rubric' "; 
       } 
       if($rh27_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhrubricas.rh27_instit = $rh27_instit "; 
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
   * Busca as rubricas do servidor pelo periodo
   *
   * @param integer $iServidor
   * @param string $sTabela
   * @param string $sSigla
   * @param integer $iMes
   * @param integer $iAno
   * @return string
   */
  public function sql_queryRubricas($iServidor, $sTabela, $sSigla, $iMes, $iAno) {

  	$sSql  = "select distinct {$sSigla}_rubric as codigo_rubrica ";
  	$sSql .= "  from {$sTabela}                                  ";
  	$sSql .= " where {$sSigla}_regist = {$iServidor}             ";
  	$sSql .= "   and {$sSigla}_anousu = {$iAno}                  ";
  	$sSql .= "   and {$sSigla}_mesusu = {$iMes}                  ";

  	return $sSql;
  }
  
   function sql_query_basesreg ( $rh27_rubric=null,$rh27_instit=null,$campos="*",$ordem=null,$dbwhere="",$basereg="", $regist){ 
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
     $sql .= " from rhrubricas ";
     $sql .= "      left join rhbasesreg on rhbasesreg.rh54_rubric = rhrubricas.rh27_rubric ";
     if(trim($basereg) != "" && $basereg != null && trim($regist) && $regist != null){
       $sql .= "                        and rhbasesreg.rh54_base   = '".$basereg."'
                                        and rhbasesreg.rh54_regist = ".$regist;
       $sql .= "    left join   basesr   on   basesr.r09_base     = '".$basereg."' 
                                        and   basesr.r09_rubric   = rhrubricas.rh27_rubric
                                        and   basesr.r09_anousu   = ".db_anofolha()."
                                        and   basesr.r09_mesusu   = ".db_mesfolha()."
					and   basesr.r09_instit   = ".db_getsession('DB_instit');
     }
     $sql2 = "";
     if($dbwhere==""){
       if($rh27_rubric!=null ){
         $sql2 .= " where rhrubricas.rh27_rubric = '$rh27_rubric' "; 
       } 
       if($rh27_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " rhrubricas.rh27_instit = $rh27_instit "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     //echo $sql;
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