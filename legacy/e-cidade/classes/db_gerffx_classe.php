<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE gerffx
class cl_gerffx { 
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
   var $r53_anousu = 0; 
   var $r53_mesusu = 0; 
   var $r53_regist = 0; 
   var $r53_rubric = null; 
   var $r53_valor = 0; 
   var $r53_pd = 0; 
   var $r53_quant = 0; 
   var $r53_lotac = null; 
   var $r53_semest = 0; 
   var $r53_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r53_anousu = int4 = Ano do Exercicio 
                 r53_mesusu = int4 = Mes do Exercicio 
                 r53_regist = int4 = Codigo do Funcionario 
                 r53_rubric = char(4) = Rubrica 
                 r53_valor = float8 = Valor 
                 r53_pd = int4 = Identifica se e Prov. ou Desc. 
                 r53_quant = float8 = Quantidade da rubrica 
                 r53_lotac = varchar(4) = Lotação 
                 r53_semest = int4 = Semestre 
                 r53_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_gerffx() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gerffx"); 
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
       $this->r53_anousu = ($this->r53_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_anousu"]:$this->r53_anousu);
       $this->r53_mesusu = ($this->r53_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_mesusu"]:$this->r53_mesusu);
       $this->r53_regist = ($this->r53_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_regist"]:$this->r53_regist);
       $this->r53_rubric = ($this->r53_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_rubric"]:$this->r53_rubric);
       $this->r53_valor = ($this->r53_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_valor"]:$this->r53_valor);
       $this->r53_pd = ($this->r53_pd == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_pd"]:$this->r53_pd);
       $this->r53_quant = ($this->r53_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_quant"]:$this->r53_quant);
       $this->r53_lotac = ($this->r53_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_lotac"]:$this->r53_lotac);
       $this->r53_semest = ($this->r53_semest == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_semest"]:$this->r53_semest);
       $this->r53_instit = ($this->r53_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_instit"]:$this->r53_instit);
     }else{
       $this->r53_anousu = ($this->r53_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_anousu"]:$this->r53_anousu);
       $this->r53_mesusu = ($this->r53_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_mesusu"]:$this->r53_mesusu);
       $this->r53_regist = ($this->r53_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_regist"]:$this->r53_regist);
       $this->r53_rubric = ($this->r53_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r53_rubric"]:$this->r53_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r53_anousu,$r53_mesusu,$r53_regist,$r53_rubric){ 
      $this->atualizacampos();
     if($this->r53_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "r53_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r53_pd == null ){ 
       $this->erro_sql = " Campo Identifica se e Prov. ou Desc. nao Informado.";
       $this->erro_campo = "r53_pd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r53_quant == null ){ 
       $this->erro_sql = " Campo Quantidade da rubrica nao Informado.";
       $this->erro_campo = "r53_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r53_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r53_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r53_semest == null ){ 
       $this->erro_sql = " Campo Semestre nao Informado.";
       $this->erro_campo = "r53_semest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r53_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r53_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r53_anousu = $r53_anousu; 
       $this->r53_mesusu = $r53_mesusu; 
       $this->r53_regist = $r53_regist; 
       $this->r53_rubric = $r53_rubric; 
     if(($this->r53_anousu == null) || ($this->r53_anousu == "") ){ 
       $this->erro_sql = " Campo r53_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r53_mesusu == null) || ($this->r53_mesusu == "") ){ 
       $this->erro_sql = " Campo r53_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r53_regist == null) || ($this->r53_regist == "") ){ 
       $this->erro_sql = " Campo r53_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r53_rubric == null) || ($this->r53_rubric == "") ){ 
       $this->erro_sql = " Campo r53_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gerffx(
                                       r53_anousu 
                                      ,r53_mesusu 
                                      ,r53_regist 
                                      ,r53_rubric 
                                      ,r53_valor 
                                      ,r53_pd 
                                      ,r53_quant 
                                      ,r53_lotac 
                                      ,r53_semest 
                                      ,r53_instit 
                       )
                values (
                                $this->r53_anousu 
                               ,$this->r53_mesusu 
                               ,$this->r53_regist 
                               ,'$this->r53_rubric' 
                               ,$this->r53_valor 
                               ,$this->r53_pd 
                               ,$this->r53_quant 
                               ,'$this->r53_lotac' 
                               ,$this->r53_semest 
                               ,$this->r53_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "calculo do ponto fixo ($this->r53_anousu."-".$this->r53_mesusu."-".$this->r53_regist."-".$this->r53_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "calculo do ponto fixo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "calculo do ponto fixo ($this->r53_anousu."-".$this->r53_mesusu."-".$this->r53_regist."-".$this->r53_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r53_anousu."-".$this->r53_mesusu."-".$this->r53_regist."-".$this->r53_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r53_anousu,$this->r53_mesusu,$this->r53_regist,$this->r53_rubric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3968,'$this->r53_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3969,'$this->r53_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3970,'$this->r53_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,3971,'$this->r53_rubric','I')");
       $resac = db_query("insert into db_acount values($acount,556,3968,'','".AddSlashes(pg_result($resaco,0,'r53_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,556,3969,'','".AddSlashes(pg_result($resaco,0,'r53_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,556,3970,'','".AddSlashes(pg_result($resaco,0,'r53_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,556,3971,'','".AddSlashes(pg_result($resaco,0,'r53_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,556,3972,'','".AddSlashes(pg_result($resaco,0,'r53_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,556,3973,'','".AddSlashes(pg_result($resaco,0,'r53_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,556,3974,'','".AddSlashes(pg_result($resaco,0,'r53_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,556,3975,'','".AddSlashes(pg_result($resaco,0,'r53_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,556,3976,'','".AddSlashes(pg_result($resaco,0,'r53_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,556,7457,'','".AddSlashes(pg_result($resaco,0,'r53_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r53_anousu=null,$r53_mesusu=null,$r53_regist=null,$r53_rubric=null) { 
      $this->atualizacampos();
     $sql = " update gerffx set ";
     $virgula = "";
     if(trim($this->r53_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r53_anousu"])){ 
       $sql  .= $virgula." r53_anousu = $this->r53_anousu ";
       $virgula = ",";
       if(trim($this->r53_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r53_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r53_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r53_mesusu"])){ 
       $sql  .= $virgula." r53_mesusu = $this->r53_mesusu ";
       $virgula = ",";
       if(trim($this->r53_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r53_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r53_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r53_regist"])){ 
       $sql  .= $virgula." r53_regist = $this->r53_regist ";
       $virgula = ",";
       if(trim($this->r53_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r53_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r53_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r53_rubric"])){ 
       $sql  .= $virgula." r53_rubric = '$this->r53_rubric' ";
       $virgula = ",";
       if(trim($this->r53_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r53_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r53_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r53_valor"])){ 
       $sql  .= $virgula." r53_valor = $this->r53_valor ";
       $virgula = ",";
       if(trim($this->r53_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "r53_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r53_pd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r53_pd"])){ 
       $sql  .= $virgula." r53_pd = $this->r53_pd ";
       $virgula = ",";
       if(trim($this->r53_pd) == null ){ 
         $this->erro_sql = " Campo Identifica se e Prov. ou Desc. nao Informado.";
         $this->erro_campo = "r53_pd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r53_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r53_quant"])){ 
       $sql  .= $virgula." r53_quant = $this->r53_quant ";
       $virgula = ",";
       if(trim($this->r53_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade da rubrica nao Informado.";
         $this->erro_campo = "r53_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r53_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r53_lotac"])){ 
       $sql  .= $virgula." r53_lotac = '$this->r53_lotac' ";
       $virgula = ",";
       if(trim($this->r53_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r53_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r53_semest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r53_semest"])){ 
       $sql  .= $virgula." r53_semest = $this->r53_semest ";
       $virgula = ",";
       if(trim($this->r53_semest) == null ){ 
         $this->erro_sql = " Campo Semestre nao Informado.";
         $this->erro_campo = "r53_semest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r53_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r53_instit"])){ 
       $sql  .= $virgula." r53_instit = $this->r53_instit ";
       $virgula = ",";
       if(trim($this->r53_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r53_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r53_anousu!=null){
       $sql .= " r53_anousu = $this->r53_anousu";
     }
     if($r53_mesusu!=null){
       $sql .= " and  r53_mesusu = $this->r53_mesusu";
     }
     if($r53_regist!=null){
       $sql .= " and  r53_regist = $this->r53_regist";
     }
     if($r53_rubric!=null){
       $sql .= " and  r53_rubric = '$this->r53_rubric'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r53_anousu,$this->r53_mesusu,$this->r53_regist,$this->r53_rubric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3968,'$this->r53_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3969,'$this->r53_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3970,'$this->r53_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,3971,'$this->r53_rubric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r53_anousu"]))
           $resac = db_query("insert into db_acount values($acount,556,3968,'".AddSlashes(pg_result($resaco,$conresaco,'r53_anousu'))."','$this->r53_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r53_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,556,3969,'".AddSlashes(pg_result($resaco,$conresaco,'r53_mesusu'))."','$this->r53_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r53_regist"]))
           $resac = db_query("insert into db_acount values($acount,556,3970,'".AddSlashes(pg_result($resaco,$conresaco,'r53_regist'))."','$this->r53_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r53_rubric"]))
           $resac = db_query("insert into db_acount values($acount,556,3971,'".AddSlashes(pg_result($resaco,$conresaco,'r53_rubric'))."','$this->r53_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r53_valor"]))
           $resac = db_query("insert into db_acount values($acount,556,3972,'".AddSlashes(pg_result($resaco,$conresaco,'r53_valor'))."','$this->r53_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r53_pd"]))
           $resac = db_query("insert into db_acount values($acount,556,3973,'".AddSlashes(pg_result($resaco,$conresaco,'r53_pd'))."','$this->r53_pd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r53_quant"]))
           $resac = db_query("insert into db_acount values($acount,556,3974,'".AddSlashes(pg_result($resaco,$conresaco,'r53_quant'))."','$this->r53_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r53_lotac"]))
           $resac = db_query("insert into db_acount values($acount,556,3975,'".AddSlashes(pg_result($resaco,$conresaco,'r53_lotac'))."','$this->r53_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r53_semest"]))
           $resac = db_query("insert into db_acount values($acount,556,3976,'".AddSlashes(pg_result($resaco,$conresaco,'r53_semest'))."','$this->r53_semest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r53_instit"]))
           $resac = db_query("insert into db_acount values($acount,556,7457,'".AddSlashes(pg_result($resaco,$conresaco,'r53_instit'))."','$this->r53_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "calculo do ponto fixo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r53_anousu."-".$this->r53_mesusu."-".$this->r53_regist."-".$this->r53_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "calculo do ponto fixo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r53_anousu."-".$this->r53_mesusu."-".$this->r53_regist."-".$this->r53_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r53_anousu."-".$this->r53_mesusu."-".$this->r53_regist."-".$this->r53_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r53_anousu=null,$r53_mesusu=null,$r53_regist=null,$r53_rubric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r53_anousu,$r53_mesusu,$r53_regist,$r53_rubric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3968,'$r53_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3969,'$r53_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3970,'$r53_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,3971,'$r53_rubric','E')");
         $resac = db_query("insert into db_acount values($acount,556,3968,'','".AddSlashes(pg_result($resaco,$iresaco,'r53_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,556,3969,'','".AddSlashes(pg_result($resaco,$iresaco,'r53_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,556,3970,'','".AddSlashes(pg_result($resaco,$iresaco,'r53_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,556,3971,'','".AddSlashes(pg_result($resaco,$iresaco,'r53_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,556,3972,'','".AddSlashes(pg_result($resaco,$iresaco,'r53_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,556,3973,'','".AddSlashes(pg_result($resaco,$iresaco,'r53_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,556,3974,'','".AddSlashes(pg_result($resaco,$iresaco,'r53_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,556,3975,'','".AddSlashes(pg_result($resaco,$iresaco,'r53_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,556,3976,'','".AddSlashes(pg_result($resaco,$iresaco,'r53_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,556,7457,'','".AddSlashes(pg_result($resaco,$iresaco,'r53_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from gerffx
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r53_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r53_anousu = $r53_anousu ";
        }
        if($r53_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r53_mesusu = $r53_mesusu ";
        }
        if($r53_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r53_regist = $r53_regist ";
        }
        if($r53_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r53_rubric = '$r53_rubric' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "calculo do ponto fixo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r53_anousu."-".$r53_mesusu."-".$r53_regist."-".$r53_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "calculo do ponto fixo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r53_anousu."-".$r53_mesusu."-".$r53_regist."-".$r53_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r53_anousu."-".$r53_mesusu."-".$r53_regist."-".$r53_rubric;
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
        $this->erro_sql   = "Record Vazio na Tabela:gerffx";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r53_anousu=null,$r53_mesusu=null,$r53_regist=null,$r53_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerffx ";
     $sql .= "      inner join db_config  on  db_config.codigo = gerffx.r53_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = gerffx.r53_anousu 
		                                   and  lotacao.r13_mesusu = gerffx.r53_mesusu 
																			 and  lotacao.r13_codigo = gerffx.r53_lotac
																			 and  lotacao.r13_instit = gerffx.r53_instit ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = gerffx.r53_anousu 
		                                   and  pessoal.r01_mesusu = gerffx.r53_mesusu 
																			 and  pessoal.r01_regist = gerffx.r53_regist
																			 and  pessoal.r01_instit = gerffx.r53_instit ";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = gerffx.r53_anousu 
		                                    and  rubricas.r06_mesusu = gerffx.r53_mesusu 
																				and  rubricas.r06_codigo = gerffx.r53_rubric 
																				and  rubricas.r06_instit = gerffx.r53_instit ";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as d on   d.r37_anousu = pessoal.r01_anousu 
		                                       and   d.r37_mesusu = pessoal.r01_mesusu 
																					 and   d.r37_funcao = pessoal.r01_funcao 
																					 and   d.r37_instit = pessoal.r01_instit ";
     $sql .= "      inner join inssirf  as d on   d.r33_anousu = pessoal.r01_anousu 
		                                        and   d.r33_mesusu = pessoal.r01_mesusu 
																						and   d.r33_codtab = pessoal.r01_tbprev 
																						and   d.r33_instit = pessoal.r01_instit ";
     $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu 
		                                      and   d.r65_mesusu = pessoal.r01_mesusu 
																					and   d.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r53_anousu!=null ){
         $sql2 .= " where gerffx.r53_anousu = $r53_anousu "; 
       } 
       if($r53_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffx.r53_mesusu = $r53_mesusu "; 
       } 
       if($r53_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffx.r53_regist = $r53_regist "; 
       } 
       if($r53_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffx.r53_rubric = '$r53_rubric' "; 
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
   function sql_query_file ( $r53_anousu=null,$r53_mesusu=null,$r53_regist=null,$r53_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerffx ";
     $sql2 = "";
     if($dbwhere==""){
       if($r53_anousu!=null ){
         $sql2 .= " where gerffx.r53_anousu = $r53_anousu "; 
       } 
       if($r53_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffx.r53_mesusu = $r53_mesusu "; 
       } 
       if($r53_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffx.r53_regist = $r53_regist "; 
       } 
       if($r53_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffx.r53_rubric = '$r53_rubric' "; 
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
   function sql_query_rhrubricas ( $r53_anousu=null,$r53_mesusu=null,$r53_regist=null,$r53_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerffx ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = gerffx.r53_rubric
		                                      and  rhrubricas.rh27_instit = gerffx.r53_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($r53_anousu!=null ){
         $sql2 .= " where gerffx.r53_anousu = $r53_anousu "; 
       } 
       if($r53_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffx.r53_mesusu = $r53_mesusu "; 
       } 
       if($r53_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffx.r53_regist = $r53_regist "; 
       } 
       if($r53_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerffx.r53_rubric = '$r53_rubric' "; 
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