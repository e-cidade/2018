<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE gerfprovs13
class cl_gerfprovs13 { 
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
   var $r94_anousu = 0; 
   var $r94_mesusu = 0; 
   var $r94_regist = 0; 
   var $r94_rubric = null; 
   var $r94_valor = 0; 
   var $r94_pd = 0; 
   var $r94_quant = 0; 
   var $r94_lotac = null; 
   var $r94_semest = 0; 
   var $r94_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r94_anousu = int4 = Ano do Exercicio 
                 r94_mesusu = int4 = Mes do Exercicio 
                 r94_regist = int4 = Codigo do Funcionario 
                 r94_rubric = char(4) = Rubrica 
                 r94_valor = float8 = valor da Rubrica 
                 r94_pd = int4 = Provento ou desconto 
                 r94_quant = float8 = Quantidade lancada na Rubrica 
                 r94_lotac = char(4) = Lotação 
                 r94_semest = int4 = Semestre do ano 
                 r94_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_gerfprovs13() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gerfprovs13"); 
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
       $this->r94_anousu = ($this->r94_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_anousu"]:$this->r94_anousu);
       $this->r94_mesusu = ($this->r94_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_mesusu"]:$this->r94_mesusu);
       $this->r94_regist = ($this->r94_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_regist"]:$this->r94_regist);
       $this->r94_rubric = ($this->r94_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_rubric"]:$this->r94_rubric);
       $this->r94_valor = ($this->r94_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_valor"]:$this->r94_valor);
       $this->r94_pd = ($this->r94_pd == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_pd"]:$this->r94_pd);
       $this->r94_quant = ($this->r94_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_quant"]:$this->r94_quant);
       $this->r94_lotac = ($this->r94_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_lotac"]:$this->r94_lotac);
       $this->r94_semest = ($this->r94_semest == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_semest"]:$this->r94_semest);
       $this->r94_instit = ($this->r94_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_instit"]:$this->r94_instit);
     }else{
       $this->r94_anousu = ($this->r94_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_anousu"]:$this->r94_anousu);
       $this->r94_mesusu = ($this->r94_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_mesusu"]:$this->r94_mesusu);
       $this->r94_regist = ($this->r94_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_regist"]:$this->r94_regist);
       $this->r94_rubric = ($this->r94_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r94_rubric"]:$this->r94_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r94_anousu,$r94_mesusu,$r94_regist,$r94_rubric){ 
      $this->atualizacampos();
     if($this->r94_valor == null ){ 
       $this->erro_sql = " Campo valor da Rubrica nao Informado.";
       $this->erro_campo = "r94_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r94_pd == null ){ 
       $this->erro_sql = " Campo Provento ou desconto nao Informado.";
       $this->erro_campo = "r94_pd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r94_quant == null ){ 
       $this->erro_sql = " Campo Quantidade lancada na Rubrica nao Informado.";
       $this->erro_campo = "r94_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r94_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r94_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r94_semest == null ){ 
       $this->erro_sql = " Campo Semestre do ano nao Informado.";
       $this->erro_campo = "r94_semest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r94_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r94_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r94_anousu = $r94_anousu; 
       $this->r94_mesusu = $r94_mesusu; 
       $this->r94_regist = $r94_regist; 
       $this->r94_rubric = $r94_rubric; 
     if(($this->r94_anousu == null) || ($this->r94_anousu == "") ){ 
       $this->erro_sql = " Campo r94_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r94_mesusu == null) || ($this->r94_mesusu == "") ){ 
       $this->erro_sql = " Campo r94_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r94_regist == null) || ($this->r94_regist == "") ){ 
       $this->erro_sql = " Campo r94_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r94_rubric == null) || ($this->r94_rubric == "") ){ 
       $this->erro_sql = " Campo r94_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gerfprovs13(
                                       r94_anousu 
                                      ,r94_mesusu 
                                      ,r94_regist 
                                      ,r94_rubric 
                                      ,r94_valor 
                                      ,r94_pd 
                                      ,r94_quant 
                                      ,r94_lotac 
                                      ,r94_semest 
                                      ,r94_instit 
                       )
                values (
                                $this->r94_anousu 
                               ,$this->r94_mesusu 
                               ,$this->r94_regist 
                               ,'$this->r94_rubric' 
                               ,$this->r94_valor 
                               ,$this->r94_pd 
                               ,$this->r94_quant 
                               ,'$this->r94_lotac' 
                               ,$this->r94_semest 
                               ,$this->r94_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Financeiro de Provisao de 13o. salario ($this->r94_anousu."-".$this->r94_mesusu."-".$this->r94_regist."-".$this->r94_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Financeiro de Provisao de 13o. salario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Financeiro de Provisao de 13o. salario ($this->r94_anousu."-".$this->r94_mesusu."-".$this->r94_regist."-".$this->r94_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r94_anousu."-".$this->r94_mesusu."-".$this->r94_regist."-".$this->r94_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r94_anousu,$this->r94_mesusu,$this->r94_regist,$this->r94_rubric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13326,'$this->r94_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,13327,'$this->r94_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,13328,'$this->r94_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,13329,'$this->r94_rubric','I')");
       $resac = db_query("insert into db_acount values($acount,2333,13326,'','".AddSlashes(pg_result($resaco,0,'r94_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2333,13327,'','".AddSlashes(pg_result($resaco,0,'r94_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2333,13328,'','".AddSlashes(pg_result($resaco,0,'r94_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2333,13329,'','".AddSlashes(pg_result($resaco,0,'r94_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2333,13330,'','".AddSlashes(pg_result($resaco,0,'r94_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2333,13331,'','".AddSlashes(pg_result($resaco,0,'r94_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2333,13332,'','".AddSlashes(pg_result($resaco,0,'r94_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2333,13333,'','".AddSlashes(pg_result($resaco,0,'r94_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2333,13334,'','".AddSlashes(pg_result($resaco,0,'r94_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2333,13335,'','".AddSlashes(pg_result($resaco,0,'r94_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r94_anousu=null,$r94_mesusu=null,$r94_regist=null,$r94_rubric=null) { 
      $this->atualizacampos();
     $sql = " update gerfprovs13 set ";
     $virgula = "";
     if(trim($this->r94_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r94_anousu"])){ 
       $sql  .= $virgula." r94_anousu = $this->r94_anousu ";
       $virgula = ",";
       if(trim($this->r94_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r94_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r94_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r94_mesusu"])){ 
       $sql  .= $virgula." r94_mesusu = $this->r94_mesusu ";
       $virgula = ",";
       if(trim($this->r94_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r94_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r94_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r94_regist"])){ 
       $sql  .= $virgula." r94_regist = $this->r94_regist ";
       $virgula = ",";
       if(trim($this->r94_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r94_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r94_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r94_rubric"])){ 
       $sql  .= $virgula." r94_rubric = '$this->r94_rubric' ";
       $virgula = ",";
       if(trim($this->r94_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r94_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r94_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r94_valor"])){ 
       $sql  .= $virgula." r94_valor = $this->r94_valor ";
       $virgula = ",";
       if(trim($this->r94_valor) == null ){ 
         $this->erro_sql = " Campo valor da Rubrica nao Informado.";
         $this->erro_campo = "r94_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r94_pd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r94_pd"])){ 
       $sql  .= $virgula." r94_pd = $this->r94_pd ";
       $virgula = ",";
       if(trim($this->r94_pd) == null ){ 
         $this->erro_sql = " Campo Provento ou desconto nao Informado.";
         $this->erro_campo = "r94_pd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r94_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r94_quant"])){ 
       $sql  .= $virgula." r94_quant = $this->r94_quant ";
       $virgula = ",";
       if(trim($this->r94_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade lancada na Rubrica nao Informado.";
         $this->erro_campo = "r94_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r94_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r94_lotac"])){ 
       $sql  .= $virgula." r94_lotac = '$this->r94_lotac' ";
       $virgula = ",";
       if(trim($this->r94_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r94_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r94_semest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r94_semest"])){ 
       $sql  .= $virgula." r94_semest = $this->r94_semest ";
       $virgula = ",";
       if(trim($this->r94_semest) == null ){ 
         $this->erro_sql = " Campo Semestre do ano nao Informado.";
         $this->erro_campo = "r94_semest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r94_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r94_instit"])){ 
       $sql  .= $virgula." r94_instit = $this->r94_instit ";
       $virgula = ",";
       if(trim($this->r94_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r94_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r94_anousu!=null){
       $sql .= " r94_anousu = $this->r94_anousu";
     }
     if($r94_mesusu!=null){
       $sql .= " and  r94_mesusu = $this->r94_mesusu";
     }
     if($r94_regist!=null){
       $sql .= " and  r94_regist = $this->r94_regist";
     }
     if($r94_rubric!=null){
       $sql .= " and  r94_rubric = '$this->r94_rubric'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r94_anousu,$this->r94_mesusu,$this->r94_regist,$this->r94_rubric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13326,'$this->r94_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,13327,'$this->r94_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,13328,'$this->r94_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,13329,'$this->r94_rubric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r94_anousu"]) || $this->r94_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2333,13326,'".AddSlashes(pg_result($resaco,$conresaco,'r94_anousu'))."','$this->r94_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r94_mesusu"]) || $this->r94_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,2333,13327,'".AddSlashes(pg_result($resaco,$conresaco,'r94_mesusu'))."','$this->r94_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r94_regist"]) || $this->r94_regist != "")
           $resac = db_query("insert into db_acount values($acount,2333,13328,'".AddSlashes(pg_result($resaco,$conresaco,'r94_regist'))."','$this->r94_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r94_rubric"]) || $this->r94_rubric != "")
           $resac = db_query("insert into db_acount values($acount,2333,13329,'".AddSlashes(pg_result($resaco,$conresaco,'r94_rubric'))."','$this->r94_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r94_valor"]) || $this->r94_valor != "")
           $resac = db_query("insert into db_acount values($acount,2333,13330,'".AddSlashes(pg_result($resaco,$conresaco,'r94_valor'))."','$this->r94_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r94_pd"]) || $this->r94_pd != "")
           $resac = db_query("insert into db_acount values($acount,2333,13331,'".AddSlashes(pg_result($resaco,$conresaco,'r94_pd'))."','$this->r94_pd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r94_quant"]) || $this->r94_quant != "")
           $resac = db_query("insert into db_acount values($acount,2333,13332,'".AddSlashes(pg_result($resaco,$conresaco,'r94_quant'))."','$this->r94_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r94_lotac"]) || $this->r94_lotac != "")
           $resac = db_query("insert into db_acount values($acount,2333,13333,'".AddSlashes(pg_result($resaco,$conresaco,'r94_lotac'))."','$this->r94_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r94_semest"]) || $this->r94_semest != "")
           $resac = db_query("insert into db_acount values($acount,2333,13334,'".AddSlashes(pg_result($resaco,$conresaco,'r94_semest'))."','$this->r94_semest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r94_instit"]) || $this->r94_instit != "")
           $resac = db_query("insert into db_acount values($acount,2333,13335,'".AddSlashes(pg_result($resaco,$conresaco,'r94_instit'))."','$this->r94_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Financeiro de Provisao de 13o. salario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r94_anousu."-".$this->r94_mesusu."-".$this->r94_regist."-".$this->r94_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Financeiro de Provisao de 13o. salario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r94_anousu."-".$this->r94_mesusu."-".$this->r94_regist."-".$this->r94_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r94_anousu."-".$this->r94_mesusu."-".$this->r94_regist."-".$this->r94_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r94_anousu=null,$r94_mesusu=null,$r94_regist=null,$r94_rubric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r94_anousu,$r94_mesusu,$r94_regist,$r94_rubric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13326,'$r94_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,13327,'$r94_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,13328,'$r94_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,13329,'$r94_rubric','E')");
         $resac = db_query("insert into db_acount values($acount,2333,13326,'','".AddSlashes(pg_result($resaco,$iresaco,'r94_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2333,13327,'','".AddSlashes(pg_result($resaco,$iresaco,'r94_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2333,13328,'','".AddSlashes(pg_result($resaco,$iresaco,'r94_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2333,13329,'','".AddSlashes(pg_result($resaco,$iresaco,'r94_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2333,13330,'','".AddSlashes(pg_result($resaco,$iresaco,'r94_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2333,13331,'','".AddSlashes(pg_result($resaco,$iresaco,'r94_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2333,13332,'','".AddSlashes(pg_result($resaco,$iresaco,'r94_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2333,13333,'','".AddSlashes(pg_result($resaco,$iresaco,'r94_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2333,13334,'','".AddSlashes(pg_result($resaco,$iresaco,'r94_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2333,13335,'','".AddSlashes(pg_result($resaco,$iresaco,'r94_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from gerfprovs13
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r94_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r94_anousu = $r94_anousu ";
        }
        if($r94_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r94_mesusu = $r94_mesusu ";
        }
        if($r94_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r94_regist = $r94_regist ";
        }
        if($r94_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r94_rubric = '$r94_rubric' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Financeiro de Provisao de 13o. salario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r94_anousu."-".$r94_mesusu."-".$r94_regist."-".$r94_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Financeiro de Provisao de 13o. salario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r94_anousu."-".$r94_mesusu."-".$r94_regist."-".$r94_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r94_anousu."-".$r94_mesusu."-".$r94_regist."-".$r94_rubric;
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
        $this->erro_sql   = "Record Vazio na Tabela:gerfprovs13";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r94_anousu=null,$r94_mesusu=null,$r94_regist=null,$r94_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfprovs13 ";
     $sql .= "      inner join db_config  on  db_config.codigo = gerfprovs13.r94_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($r94_anousu!=null ){
         $sql2 .= " where gerfprovs13.r94_anousu = $r94_anousu "; 
       } 
       if($r94_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovs13.r94_mesusu = $r94_mesusu "; 
       } 
       if($r94_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovs13.r94_regist = $r94_regist "; 
       } 
       if($r94_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovs13.r94_rubric = '$r94_rubric' "; 
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
   function sql_query_file ( $r94_anousu=null,$r94_mesusu=null,$r94_regist=null,$r94_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfprovs13 ";
     $sql2 = "";
     if($dbwhere==""){
       if($r94_anousu!=null ){
         $sql2 .= " where gerfprovs13.r94_anousu = $r94_anousu "; 
       } 
       if($r94_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovs13.r94_mesusu = $r94_mesusu "; 
       } 
       if($r94_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovs13.r94_regist = $r94_regist "; 
       } 
       if($r94_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfprovs13.r94_rubric = '$r94_rubric' "; 
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