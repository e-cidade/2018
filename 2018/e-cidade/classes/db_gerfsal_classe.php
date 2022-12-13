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
//CLASSE DA ENTIDADE gerfsal
class cl_gerfsal { 
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
   var $r14_anousu = 0; 
   var $r14_mesusu = 0; 
   var $r14_regist = 0; 
   var $r14_rubric = null; 
   var $r14_valor = 0; 
   var $r14_pd = 0; 
   var $r14_quant = 0; 
   var $r14_lotac = null; 
   var $r14_semest = 0; 
   var $r14_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r14_anousu = int4 = Ano do Exercicio 
                 r14_mesusu = int4 = Mes do Exercicio 
                 r14_regist = int4 = Codigo do Funcionario 
                 r14_rubric = char(4) = Rubrica 
                 r14_valor = float8 = Valor 
                 r14_pd = int4 = Indicador 
                 r14_quant = float8 = Quantidade 
                 r14_lotac = char(4) = Lotação 
                 r14_semest = int4 = Semestre 
                 r14_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_gerfsal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gerfsal"); 
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
       $this->r14_anousu = ($this->r14_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_anousu"]:$this->r14_anousu);
       $this->r14_mesusu = ($this->r14_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_mesusu"]:$this->r14_mesusu);
       $this->r14_regist = ($this->r14_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_regist"]:$this->r14_regist);
       $this->r14_rubric = ($this->r14_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_rubric"]:$this->r14_rubric);
       $this->r14_valor = ($this->r14_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_valor"]:$this->r14_valor);
       $this->r14_pd = ($this->r14_pd == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_pd"]:$this->r14_pd);
       $this->r14_quant = ($this->r14_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_quant"]:$this->r14_quant);
       $this->r14_lotac = ($this->r14_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_lotac"]:$this->r14_lotac);
       $this->r14_semest = ($this->r14_semest == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_semest"]:$this->r14_semest);
       $this->r14_instit = ($this->r14_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_instit"]:$this->r14_instit);
     }else{
       $this->r14_anousu = ($this->r14_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_anousu"]:$this->r14_anousu);
       $this->r14_mesusu = ($this->r14_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_mesusu"]:$this->r14_mesusu);
       $this->r14_regist = ($this->r14_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_regist"]:$this->r14_regist);
       $this->r14_rubric = ($this->r14_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r14_rubric"]:$this->r14_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric){ 
      $this->atualizacampos();
     if($this->r14_valor == null ){ 
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "r14_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r14_pd == null ){ 
       $this->erro_sql = " Campo Indicador não informado.";
       $this->erro_campo = "r14_pd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r14_quant == null ){ 
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "r14_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r14_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r14_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r14_semest == null ){ 
       $this->erro_sql = " Campo Semestre não informado.";
       $this->erro_campo = "r14_semest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r14_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao não informado.";
       $this->erro_campo = "r14_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r14_anousu = $r14_anousu; 
       $this->r14_mesusu = $r14_mesusu; 
       $this->r14_regist = $r14_regist; 
       $this->r14_rubric = $r14_rubric; 
     if(($this->r14_anousu == null) || ($this->r14_anousu == "") ){ 
       $this->erro_sql = " Campo r14_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r14_mesusu == null) || ($this->r14_mesusu == "") ){ 
       $this->erro_sql = " Campo r14_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r14_regist == null) || ($this->r14_regist == "") ){ 
       $this->erro_sql = " Campo r14_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r14_rubric == null) || ($this->r14_rubric == "") ){ 
       $this->erro_sql = " Campo r14_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gerfsal(
                                       r14_anousu 
                                      ,r14_mesusu 
                                      ,r14_regist 
                                      ,r14_rubric 
                                      ,r14_valor 
                                      ,r14_pd 
                                      ,r14_quant 
                                      ,r14_lotac 
                                      ,r14_semest 
                                      ,r14_instit 
                       )
                values (
                                $this->r14_anousu 
                               ,$this->r14_mesusu 
                               ,$this->r14_regist 
                               ,'$this->r14_rubric' 
                               ,$this->r14_valor 
                               ,$this->r14_pd 
                               ,$this->r14_quant 
                               ,'$this->r14_lotac' 
                               ,$this->r14_semest 
                               ,$this->r14_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Calculo do Salario ($this->r14_anousu."-".$this->r14_mesusu."-".$this->r14_regist."-".$this->r14_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Calculo do Salario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Calculo do Salario ($this->r14_anousu."-".$this->r14_mesusu."-".$this->r14_regist."-".$this->r14_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r14_anousu."-".$this->r14_mesusu."-".$this->r14_regist."-".$this->r14_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r14_anousu,$this->r14_mesusu,$this->r14_regist,$this->r14_rubric  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3996,'$this->r14_anousu','I')");
         $resac = db_query("insert into db_acountkey values($acount,3997,'$this->r14_mesusu','I')");
         $resac = db_query("insert into db_acountkey values($acount,3998,'$this->r14_regist','I')");
         $resac = db_query("insert into db_acountkey values($acount,3999,'$this->r14_rubric','I')");
         $resac = db_query("insert into db_acount values($acount,559,3996,'','".AddSlashes(pg_result($resaco,0,'r14_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,559,3997,'','".AddSlashes(pg_result($resaco,0,'r14_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,559,3998,'','".AddSlashes(pg_result($resaco,0,'r14_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,559,3999,'','".AddSlashes(pg_result($resaco,0,'r14_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,559,4000,'','".AddSlashes(pg_result($resaco,0,'r14_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,559,4001,'','".AddSlashes(pg_result($resaco,0,'r14_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,559,4002,'','".AddSlashes(pg_result($resaco,0,'r14_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,559,4003,'','".AddSlashes(pg_result($resaco,0,'r14_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,559,4004,'','".AddSlashes(pg_result($resaco,0,'r14_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,559,7460,'','".AddSlashes(pg_result($resaco,0,'r14_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($r14_anousu=null,$r14_mesusu=null,$r14_regist=null,$r14_rubric=null) { 
      $this->atualizacampos();
     $sql = " update gerfsal set ";
     $virgula = "";
     if(trim($this->r14_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r14_anousu"])){ 
       $sql  .= $virgula." r14_anousu = $this->r14_anousu ";
       $virgula = ",";
       if(trim($this->r14_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio não informado.";
         $this->erro_campo = "r14_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r14_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r14_mesusu"])){ 
       $sql  .= $virgula." r14_mesusu = $this->r14_mesusu ";
       $virgula = ",";
       if(trim($this->r14_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio não informado.";
         $this->erro_campo = "r14_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r14_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r14_regist"])){ 
       $sql  .= $virgula." r14_regist = $this->r14_regist ";
       $virgula = ",";
       if(trim($this->r14_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario não informado.";
         $this->erro_campo = "r14_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r14_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r14_rubric"])){ 
       $sql  .= $virgula." r14_rubric = '$this->r14_rubric' ";
       $virgula = ",";
       if(trim($this->r14_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "r14_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r14_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r14_valor"])){ 
       $sql  .= $virgula." r14_valor = $this->r14_valor ";
       $virgula = ",";
       if(trim($this->r14_valor) == null ){ 
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "r14_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r14_pd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r14_pd"])){ 
       $sql  .= $virgula." r14_pd = $this->r14_pd ";
       $virgula = ",";
       if(trim($this->r14_pd) == null ){ 
         $this->erro_sql = " Campo Indicador não informado.";
         $this->erro_campo = "r14_pd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r14_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r14_quant"])){ 
       $sql  .= $virgula." r14_quant = $this->r14_quant ";
       $virgula = ",";
       if(trim($this->r14_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "r14_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r14_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r14_lotac"])){ 
       $sql  .= $virgula." r14_lotac = '$this->r14_lotac' ";
       $virgula = ",";
       if(trim($this->r14_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r14_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r14_semest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r14_semest"])){ 
       $sql  .= $virgula." r14_semest = $this->r14_semest ";
       $virgula = ",";
       if(trim($this->r14_semest) == null ){ 
         $this->erro_sql = " Campo Semestre não informado.";
         $this->erro_campo = "r14_semest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r14_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r14_instit"])){ 
       $sql  .= $virgula." r14_instit = $this->r14_instit ";
       $virgula = ",";
       if(trim($this->r14_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao não informado.";
         $this->erro_campo = "r14_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r14_anousu!=null){
       $sql .= " r14_anousu = $this->r14_anousu";
     }
     if($r14_mesusu!=null){
       $sql .= " and  r14_mesusu = $this->r14_mesusu";
     }
     if($r14_regist!=null){
       $sql .= " and  r14_regist = $this->r14_regist";
     }
     if($r14_rubric!=null){
       $sql .= " and  r14_rubric = '$this->r14_rubric'";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r14_anousu,$this->r14_mesusu,$this->r14_regist,$this->r14_rubric));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,3996,'$this->r14_anousu','A')");
           $resac = db_query("insert into db_acountkey values($acount,3997,'$this->r14_mesusu','A')");
           $resac = db_query("insert into db_acountkey values($acount,3998,'$this->r14_regist','A')");
           $resac = db_query("insert into db_acountkey values($acount,3999,'$this->r14_rubric','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r14_anousu"]) || $this->r14_anousu != "")
             $resac = db_query("insert into db_acount values($acount,559,3996,'".AddSlashes(pg_result($resaco,$conresaco,'r14_anousu'))."','$this->r14_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r14_mesusu"]) || $this->r14_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,559,3997,'".AddSlashes(pg_result($resaco,$conresaco,'r14_mesusu'))."','$this->r14_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r14_regist"]) || $this->r14_regist != "")
             $resac = db_query("insert into db_acount values($acount,559,3998,'".AddSlashes(pg_result($resaco,$conresaco,'r14_regist'))."','$this->r14_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r14_rubric"]) || $this->r14_rubric != "")
             $resac = db_query("insert into db_acount values($acount,559,3999,'".AddSlashes(pg_result($resaco,$conresaco,'r14_rubric'))."','$this->r14_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r14_valor"]) || $this->r14_valor != "")
             $resac = db_query("insert into db_acount values($acount,559,4000,'".AddSlashes(pg_result($resaco,$conresaco,'r14_valor'))."','$this->r14_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r14_pd"]) || $this->r14_pd != "")
             $resac = db_query("insert into db_acount values($acount,559,4001,'".AddSlashes(pg_result($resaco,$conresaco,'r14_pd'))."','$this->r14_pd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r14_quant"]) || $this->r14_quant != "")
             $resac = db_query("insert into db_acount values($acount,559,4002,'".AddSlashes(pg_result($resaco,$conresaco,'r14_quant'))."','$this->r14_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r14_lotac"]) || $this->r14_lotac != "")
             $resac = db_query("insert into db_acount values($acount,559,4003,'".AddSlashes(pg_result($resaco,$conresaco,'r14_lotac'))."','$this->r14_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r14_semest"]) || $this->r14_semest != "")
             $resac = db_query("insert into db_acount values($acount,559,4004,'".AddSlashes(pg_result($resaco,$conresaco,'r14_semest'))."','$this->r14_semest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r14_instit"]) || $this->r14_instit != "")
             $resac = db_query("insert into db_acount values($acount,559,7460,'".AddSlashes(pg_result($resaco,$conresaco,'r14_instit'))."','$this->r14_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calculo do Salario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r14_anousu."-".$this->r14_mesusu."-".$this->r14_regist."-".$this->r14_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Calculo do Salario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r14_anousu."-".$this->r14_mesusu."-".$this->r14_regist."-".$this->r14_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r14_anousu."-".$this->r14_mesusu."-".$this->r14_regist."-".$this->r14_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($r14_anousu=null,$r14_mesusu=null,$r14_regist=null,$r14_rubric=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($r14_anousu,$r14_mesusu,$r14_regist,$r14_rubric));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,3996,'$r14_anousu','E')");
           $resac  = db_query("insert into db_acountkey values($acount,3997,'$r14_mesusu','E')");
           $resac  = db_query("insert into db_acountkey values($acount,3998,'$r14_regist','E')");
           $resac  = db_query("insert into db_acountkey values($acount,3999,'$r14_rubric','E')");
           $resac  = db_query("insert into db_acount values($acount,559,3996,'','".AddSlashes(pg_result($resaco,$iresaco,'r14_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,559,3997,'','".AddSlashes(pg_result($resaco,$iresaco,'r14_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,559,3998,'','".AddSlashes(pg_result($resaco,$iresaco,'r14_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,559,3999,'','".AddSlashes(pg_result($resaco,$iresaco,'r14_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,559,4000,'','".AddSlashes(pg_result($resaco,$iresaco,'r14_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,559,4001,'','".AddSlashes(pg_result($resaco,$iresaco,'r14_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,559,4002,'','".AddSlashes(pg_result($resaco,$iresaco,'r14_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,559,4003,'','".AddSlashes(pg_result($resaco,$iresaco,'r14_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,559,4004,'','".AddSlashes(pg_result($resaco,$iresaco,'r14_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,559,7460,'','".AddSlashes(pg_result($resaco,$iresaco,'r14_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from gerfsal
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($r14_anousu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r14_anousu = $r14_anousu ";
        }
        if (!empty($r14_mesusu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r14_mesusu = $r14_mesusu ";
        }
        if (!empty($r14_regist)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r14_regist = $r14_regist ";
        }
        if (!empty($r14_rubric)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r14_rubric = '$r14_rubric' ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calculo do Salario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r14_anousu."-".$r14_mesusu."-".$r14_regist."-".$r14_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Calculo do Salario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r14_anousu."-".$r14_mesusu."-".$r14_regist."-".$r14_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r14_anousu."-".$r14_mesusu."-".$r14_regist."-".$r14_rubric;
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
        $this->erro_sql   = "Record Vazio na Tabela:gerfsal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($r14_anousu = null,$r14_mesusu = null,$r14_regist = null,$r14_rubric = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from gerfsal ";
     $sql .= "      inner join db_config  on  db_config.codigo = gerfsal.r14_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = gerfsal.r14_anousu and  lotacao.r13_mesusu = gerfsal.r14_mesusu and  lotacao.r13_codigo = gerfsal.r14_lotac";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = gerfsal.r14_anousu and  pessoal.r01_mesusu = gerfsal.r14_mesusu and  pessoal.r01_regist = gerfsal.r14_regist";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = gerfsal.r14_anousu and  rubricas.r06_mesusu = gerfsal.r14_mesusu and  rubricas.r06_codigo = gerfsal.r14_rubric";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join db_config  on  db_config.codigo = lotacao.r13_instit";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      left  join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu and  funcao.r37_mesusu = pessoal.r01_mesusu and  funcao.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join lotacao  as b on   b.r13_anousu = pessoal.r01_anousu and   b.r13_mesusu = pessoal.r01_mesusu and   b.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu and  cargo.r65_mesusu = pessoal.r01_mesusu and  cargo.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join db_config  on  db_config.codigo = rubricas.r06_instit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($r14_anousu)) {
         $sql2 .= " where gerfsal.r14_anousu = $r14_anousu "; 
       } 
       if (!empty($r14_mesusu)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_mesusu = $r14_mesusu "; 
       } 
       if (!empty($r14_regist)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_regist = $r14_regist "; 
       } 
       if (!empty($r14_rubric)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_rubric = '$r14_rubric' "; 
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
   public function sql_query_file ($r14_anousu = null,$r14_mesusu = null,$r14_regist = null,$r14_rubric = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from gerfsal ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($r14_anousu)){
         $sql2 .= " where gerfsal.r14_anousu = $r14_anousu "; 
       } 
       if (!empty($r14_mesusu)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_mesusu = $r14_mesusu "; 
       } 
       if (!empty($r14_regist)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_regist = $r14_regist "; 
       } 
       if (!empty($r14_rubric)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_rubric = '$r14_rubric' "; 
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

   function sql_query_seleciona ( $r14_anousu=null,$r14_mesusu=null,$r14_regist=null,$r14_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfsal ";
     $sql .= "      inner join rhpessoal   on  rhpessoal.rh01_regist = gerfsal.r14_regist ";
     $sql .= "      inner join rhpessoalmov   on gerfsal.r14_regist  = rhpessoalmov.rh02_regist 
		                                         and ".db_anofolha()." = rhpessoalmov.rh02_anousu 
																						 and ".db_mesfolha()." = rhpessoalmov.rh02_mesusu 
																						 and gerfsal.r14_instit = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = gerfsal.r14_rubric 
		                                      and  rhrubricas.rh27_instit = gerfsal.r14_instit ";
     $sql .= "      inner join rhlota      on  rhlota.r70_codigo = to_number(gerfsal.r14_lotac, '9999')::integer
		                                      and  rhlota.r70_instit = gerfsal.r14_instit ";
     $sql .= "      inner join cgm         on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r14_anousu!=null ){
         $sql2 .= " where gerfsal.r14_anousu = $r14_anousu "; 
       } 
       if($r14_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_mesusu = $r14_mesusu "; 
       } 
       if($r14_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_regist = $r14_regist "; 
       } 
       if($r14_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_rubric = '$r14_rubric' "; 
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
   function sql_query_rhrubricas ( $r14_anousu=null,$r14_mesusu=null,$r14_regist=null,$r14_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfsal ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = gerfsal.r14_rubric
		                                      and  rhrubricas.rh27_instit = gerfsal.r14_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($r14_anousu!=null ){
         $sql2 .= " where gerfsal.r14_anousu = $r14_anousu "; 
       } 
       if($r14_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_mesusu = $r14_mesusu "; 
       } 
       if($r14_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_regist = $r14_regist "; 
       } 
       if($r14_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_rubric = '$r14_rubric' "; 
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
   function sql_query_servincsal($r14_anousu=null, $r14_mesusu=null, $r14_regist=null, $r14_rubric=null, $campos="*", $ordem=null, $dbwhere="") {
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
     $sql .= " from gerfsal ";
     $sql .= "      inner join rhresponsavelregist on rhresponsavelregist.rh108_regist = gerfsal.r14_regist ";
     $sql2 = "";
     if($dbwhere==""){
       if($r14_anousu!=null ){
         $sql2 .= " where gerfsal.r14_anousu = $r14_anousu "; 
       } 
       if($r14_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_mesusu = $r14_mesusu "; 
       } 
       if($r14_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_regist = $r14_regist "; 
       } 
       if($r14_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfsal.r14_rubric = '$r14_rubric' "; 
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
   * Retorna dados que estao na rhhistoricocalculo e insere 
   * novamente na gerfsal na competência informada.
   * @param  Integer $iAno
   * @param  Integer $iMes
   * @return boolean
   */
  public function retornarDadosGerfsal($iAno, $iMes){

    $sSql  = " insert into gerfsal( ";
    $sSql .= "     r14_anousu,";
    $sSql .= "     r14_mesusu,";
    $sSql .= "     r14_regist,";
    $sSql .= "     r14_rubric,";
    $sSql .= "     r14_valor, ";
    $sSql .= "     r14_pd,    ";
    $sSql .= "     r14_quant, ";
    $sSql .= "     r14_lotac, ";
    $sSql .= "     r14_semest,";
    $sSql .= "     r14_instit";
    $sSql .= " )";
    $sSql .= " ";
    $sSql .= " select rh141_anousu,";
    $sSql .= "        rh141_mesusu,";
    $sSql .= "        rh143_regist,";
    $sSql .= "        rh143_rubrica,";
    $sSql .= "        rh143_valor,";
    $sSql .= "        rh143_tipoevento,";
    $sSql .= "        rh143_quantidade,";
    $sSql .= "        rh02_lota,";
    $sSql .= "        1,";
    $sSql .= "        rh141_instit";
    $sSql .= "        from (";
    $sSql .= "          select rh141_anousu, ";
    $sSql .= "                 rh141_mesusu, ";
    $sSql .= "                 rh143_regist, ";
    $sSql .= "                 rh143_rubrica, ";
    $sSql .= "                 sum(rh143_valor) as rh143_valor, ";
    $sSql .= "                 rh143_tipoevento, ";
    $sSql .= "                 max(rh143_quantidade) as rh143_quantidade, ";
    $sSql .= "                 rh141_instit";
    $sSql .= "            from rhfolhapagamento";
    $sSql .= "                 inner join rhhistoricocalculo on rh141_sequencial = rh143_folhapagamento";
    $sSql .= "           where rh141_anousu     = {$iAno}";
    $sSql .= "             and rh141_mesusu     = {$iMes}";
    $sSql .= "             and rh141_tipofolha in (1, 6)";
    $sSql .= "           group by rh141_anousu, rh141_mesusu, rh143_regist, rh143_rubrica, rh143_tipoevento, rh141_instit";
    $sSql .= "             ) as base";
    $sSql .= "             inner join rhpessoalmov on rh02_anousu      = base.rh141_anousu";
    $sSql .= "                                    and rh02_mesusu      = rh141_mesusu";
    $sSql .= "                                    and rh02_regist      = rh143_regist";
    $sSql .= "                                    and rh02_instit      = rh141_instit;";

    return $sSql;
  }

  public function migraGerfSal($iInstituicao) {

    $sSql  = "    create table w_migracao_rhfolhapagamento_salario as                                                                                           ";                                         
    $sSql .= "select distinct r14_anousu,                                                                                                                       ";           
    $sSql .= "                r14_mesusu,                                                                                                                       ";           
    $sSql .= "                r14_instit                                                                                                                        ";          
    $sSql .= "  from gerfsal                                                                                                                                    ";
    $sSql .= " inner join pontofs  on r14_regist  = r10_regist                                                                                                  ";                                
    $sSql .= "                    and r14_anousu  = r10_anousu                                                                                                  ";
    $sSql .= "                    and r14_mesusu  = r10_mesusu                                                                                                  ";
    $sSql .= "                    and r10_instit  = {$iInstituicao}                                                                                             ";
    $sSql .= "order by r14_anousu asc,                                                                                                                          ";
    $sSql .= "         r14_mesusu asc;                                                                                                                          ";
    
    $sSql .= "insert into rhfolhapagamento                                                                                                                      ";
    $sSql .= "select nextval('rhfolhapagamento_rh141_sequencial_seq'),                                                                                          ";
    $sSql .= "       0,                                                                                                                                         ";
    $sSql .= "       r14_anousu,                                                                                                                                ";
    $sSql .= "       r14_mesusu,                                                                                                                                ";
    $sSql .= "       r14_anousu,                                                                                                                                ";
    $sSql .= "       r14_mesusu,                                                                                                                                ";
    $sSql .= "       r14_instit,                                                                                                                                ";
    $sSql .= "       1,                                                                                                                                         ";
    $sSql .= "       false,                                                                                                                                     ";       
    $sSql .= "       'Folha Salário da competência: ' || r14_anousu || '/' || r14_mesusu || ' gerada automaticamente.'                                          ";
    $sSql .= "  from w_migracao_rhfolhapagamento_salario                                                                                                        ";
    $sSql .= "order by r14_anousu asc,                                                                                                                          ";
    $sSql .= "         r14_mesusu asc;                                                                                                                          ";
    
    $sSql .= "create table w_ultimafolhadecadacompetencia_salario as                                                                                            ";
    $sSql .= "select 0 as ultimafolha,                                                                                                                          ";
    $sSql .= "       rh141_anousu,                                                                                                                              ";
    $sSql .= "       rh141_mesusu,                                                                                                                              ";
    $sSql .= "       rh141_instit                                                                                                                               ";
    $sSql .= "  from rhfolhapagamento                                                                                                                           ";
    $sSql .= "where rh141_tipofolha = 1                                                                                                                         ";
    $sSql .= "  and rh141_instit    = {$iInstituicao}                                                                                                           ";
    $sSql .= "group by rh141_anousu,rh141_mesusu, rh141_instit;                                                                                                 ";

    $sSql .= "insert into rhhistoricoponto                                                                                                                      ";
    $sSql .= "  (rh144_sequencial,rh144_regist,rh144_folhapagamento,rh144_rubrica,rh144_quantidade,rh144_valor)                                                 ";
    $sSql .= "select nextval('rhhistoricoponto_rh144_sequencial_seq'), * from (                                                                                 ";
    $sSql .= "select distinct                                                                                                                                   ";
    $sSql .= "       r10_regist,                                                                                                                                ";
    $sSql .= "       rhfolhapagamento.rh141_sequencial,                                                                                                         ";
    $sSql .= "       r10_rubric,                                                                                                                                ";
    $sSql .= "       r10_quant,                                                                                                                                 ";
    $sSql .= "       r10_valor                                                                                                                                  ";
    $sSql .= "  from pontofs                                                                                                                                    ";
    $sSql .= " inner join w_ultimafolhadecadacompetencia_salario  on w_ultimafolhadecadacompetencia_salario.rh141_anousu  = r10_anousu                          ";
    $sSql .= "                                                   and w_ultimafolhadecadacompetencia_salario.rh141_mesusu  = r10_mesusu                          ";
    $sSql .= "                                                   and w_ultimafolhadecadacompetencia_salario.rh141_instit  = r10_instit                          ";
    $sSql .= " inner join rhfolhapagamento                        on w_ultimafolhadecadacompetencia_salario.rh141_mesusu  = rhfolhapagamento.rh141_mesusu       ";
    $sSql .= "                                                   and w_ultimafolhadecadacompetencia_salario.rh141_anousu  = rhfolhapagamento.rh141_anousu       ";
    $sSql .= "                                                   and w_ultimafolhadecadacompetencia_salario.rh141_instit  = rhfolhapagamento.rh141_instit       ";
    $sSql .= "                                                   and w_ultimafolhadecadacompetencia_salario.ultimafolha   = 0                                   ";
    $sSql .= "                                                   and rhfolhapagamento.rh141_tipofolha                     = 1                                   ";
    $sSql .= "                                                                                                                                                  ";
    $sSql .= "order by rh141_sequencial) as x;                                                                                                                  ";

    $sSql .= "insert into rhhistoricocalculo                                                                                                                    ";
    $sSql .= "select nextval('rhhistoricocalculo_rh143_sequencial_seq'),                                                                                        ";
    $sSql .= "       r14_regist,                                                                                                                                ";
    $sSql .= "       rhfolhapagamento.rh141_sequencial,                                                                                                         ";
    $sSql .= "       r14_rubric,                                                                                                                                ";
    $sSql .= "       r14_quant,                                                                                                                                 ";
    $sSql .= "       r14_valor,                                                                                                                                 ";
    $sSql .= "       r14_pd                                                                                                                                     ";
    $sSql .= "  from gerfsal                                                                                                                                    ";
    $sSql .= " inner join rhfolhapagamento                 on  r14_anousu      = rh141_anousu                                                                   ";
    $sSql .= "                                            and  r14_mesusu      = rh141_mesusu                                                                   ";
    $sSql .= "                                            and  r14_instit      = rh141_instit                                                                   ";
    $sSql .= "                                            and  rh141_tipofolha = 1                                                                              ";
    $sSql .= "order by rh141_sequencial;                                                                                                                        ";

    $sSql .= "insert into rhfolhapagamento                                                                                                                      ";
    $sSql .= "select nextval('rhfolhapagamento_rh141_sequencial_seq'),                                                                                          ";
    $sSql .= "       0,                                                                                                                                         ";
    $sSql .= "       ".db_anofolha().",                                                                                                                         ";
    $sSql .= "       ".db_mesfolha().",                                                                                                                         ";
    $sSql .= "       ".db_anofolha().",                                                                                                                         ";
    $sSql .= "       ".db_mesfolha().",                                                                                                                         ";
    $sSql .= "       {$iInstituicao},                                                                                                                           ";
    $sSql .= "       1,                                                                                                                                         ";
    $sSql .= "       false,                                                                                                                                     ";       
    $sSql .= "       'Folha Salário da competência: ' || ".db_anofolha()." || '/' || ".db_mesfolha()." || ' gerada automaticamente.';                           ";

    return $sSql;
    
  }

}
