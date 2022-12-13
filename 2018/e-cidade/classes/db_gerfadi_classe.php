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
//CLASSE DA ENTIDADE gerfadi
class cl_gerfadi { 
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
   var $r22_anousu = 0; 
   var $r22_mesusu = 0; 
   var $r22_regist = 0; 
   var $r22_rubric = null; 
   var $r22_valor = 0; 
   var $r22_pd = 0; 
   var $r22_quant = 0; 
   var $r22_lotac = null; 
   var $r22_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r22_anousu = int4 = Ano do Exercicio 
                 r22_mesusu = int4 = Mes do Exercicio 
                 r22_regist = int4 = Codigo do Funcionario 
                 r22_rubric = char(4) = Rubrica 
                 r22_valor = float8 = Valor do Ponto 
                 r22_pd = int4 = Indicar se e Prov. ou Desconto 
                 r22_quant = float8 = Quantidade Lancada no Ponto 
                 r22_lotac = varchar(4) = Lotação 
                 r22_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_gerfadi() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gerfadi"); 
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
       $this->r22_anousu = ($this->r22_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_anousu"]:$this->r22_anousu);
       $this->r22_mesusu = ($this->r22_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_mesusu"]:$this->r22_mesusu);
       $this->r22_regist = ($this->r22_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_regist"]:$this->r22_regist);
       $this->r22_rubric = ($this->r22_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_rubric"]:$this->r22_rubric);
       $this->r22_valor = ($this->r22_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_valor"]:$this->r22_valor);
       $this->r22_pd = ($this->r22_pd == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_pd"]:$this->r22_pd);
       $this->r22_quant = ($this->r22_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_quant"]:$this->r22_quant);
       $this->r22_lotac = ($this->r22_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_lotac"]:$this->r22_lotac);
       $this->r22_instit = ($this->r22_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_instit"]:$this->r22_instit);
     }else{
       $this->r22_anousu = ($this->r22_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_anousu"]:$this->r22_anousu);
       $this->r22_mesusu = ($this->r22_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_mesusu"]:$this->r22_mesusu);
       $this->r22_regist = ($this->r22_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_regist"]:$this->r22_regist);
       $this->r22_rubric = ($this->r22_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r22_rubric"]:$this->r22_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r22_anousu,$r22_mesusu,$r22_regist,$r22_rubric){ 
      $this->atualizacampos();
     if($this->r22_valor == null ){ 
       $this->erro_sql = " Campo Valor do Ponto nao Informado.";
       $this->erro_campo = "r22_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r22_pd == null ){ 
       $this->erro_sql = " Campo Indicar se e Prov. ou Desconto nao Informado.";
       $this->erro_campo = "r22_pd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r22_quant == null ){ 
       $this->erro_sql = " Campo Quantidade Lancada no Ponto nao Informado.";
       $this->erro_campo = "r22_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r22_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r22_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r22_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r22_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r22_anousu = $r22_anousu; 
       $this->r22_mesusu = $r22_mesusu; 
       $this->r22_regist = $r22_regist; 
       $this->r22_rubric = $r22_rubric; 
     if(($this->r22_anousu == null) || ($this->r22_anousu == "") ){ 
       $this->erro_sql = " Campo r22_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r22_mesusu == null) || ($this->r22_mesusu == "") ){ 
       $this->erro_sql = " Campo r22_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r22_regist == null) || ($this->r22_regist == "") ){ 
       $this->erro_sql = " Campo r22_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r22_rubric == null) || ($this->r22_rubric == "") ){ 
       $this->erro_sql = " Campo r22_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gerfadi(
                                       r22_anousu 
                                      ,r22_mesusu 
                                      ,r22_regist 
                                      ,r22_rubric 
                                      ,r22_valor 
                                      ,r22_pd 
                                      ,r22_quant 
                                      ,r22_lotac 
                                      ,r22_instit 
                       )
                values (
                                $this->r22_anousu 
                               ,$this->r22_mesusu 
                               ,$this->r22_regist 
                               ,'$this->r22_rubric' 
                               ,$this->r22_valor 
                               ,$this->r22_pd 
                               ,$this->r22_quant 
                               ,'$this->r22_lotac' 
                               ,$this->r22_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Calculo de Adiantamento ($this->r22_anousu."-".$this->r22_mesusu."-".$this->r22_regist."-".$this->r22_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Calculo de Adiantamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Calculo de Adiantamento ($this->r22_anousu."-".$this->r22_mesusu."-".$this->r22_regist."-".$this->r22_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r22_anousu."-".$this->r22_mesusu."-".$this->r22_regist."-".$this->r22_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r22_anousu,$this->r22_mesusu,$this->r22_regist,$this->r22_rubric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3941,'$this->r22_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3942,'$this->r22_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3943,'$this->r22_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,3944,'$this->r22_rubric','I')");
       $resac = db_query("insert into db_acount values($acount,553,3941,'','".AddSlashes(pg_result($resaco,0,'r22_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,553,3942,'','".AddSlashes(pg_result($resaco,0,'r22_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,553,3943,'','".AddSlashes(pg_result($resaco,0,'r22_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,553,3944,'','".AddSlashes(pg_result($resaco,0,'r22_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,553,3945,'','".AddSlashes(pg_result($resaco,0,'r22_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,553,3946,'','".AddSlashes(pg_result($resaco,0,'r22_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,553,3947,'','".AddSlashes(pg_result($resaco,0,'r22_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,553,3948,'','".AddSlashes(pg_result($resaco,0,'r22_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,553,7454,'','".AddSlashes(pg_result($resaco,0,'r22_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r22_anousu=null,$r22_mesusu=null,$r22_regist=null,$r22_rubric=null) { 
      $this->atualizacampos();
     $sql = " update gerfadi set ";
     $virgula = "";
     if(trim($this->r22_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r22_anousu"])){ 
       $sql  .= $virgula." r22_anousu = $this->r22_anousu ";
       $virgula = ",";
       if(trim($this->r22_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r22_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r22_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r22_mesusu"])){ 
       $sql  .= $virgula." r22_mesusu = $this->r22_mesusu ";
       $virgula = ",";
       if(trim($this->r22_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r22_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r22_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r22_regist"])){ 
       $sql  .= $virgula." r22_regist = $this->r22_regist ";
       $virgula = ",";
       if(trim($this->r22_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r22_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r22_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r22_rubric"])){ 
       $sql  .= $virgula." r22_rubric = '$this->r22_rubric' ";
       $virgula = ",";
       if(trim($this->r22_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r22_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r22_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r22_valor"])){ 
       $sql  .= $virgula." r22_valor = $this->r22_valor ";
       $virgula = ",";
       if(trim($this->r22_valor) == null ){ 
         $this->erro_sql = " Campo Valor do Ponto nao Informado.";
         $this->erro_campo = "r22_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r22_pd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r22_pd"])){ 
       $sql  .= $virgula." r22_pd = $this->r22_pd ";
       $virgula = ",";
       if(trim($this->r22_pd) == null ){ 
         $this->erro_sql = " Campo Indicar se e Prov. ou Desconto nao Informado.";
         $this->erro_campo = "r22_pd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r22_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r22_quant"])){ 
       $sql  .= $virgula." r22_quant = $this->r22_quant ";
       $virgula = ",";
       if(trim($this->r22_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade Lancada no Ponto nao Informado.";
         $this->erro_campo = "r22_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r22_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r22_lotac"])){ 
       $sql  .= $virgula." r22_lotac = '$this->r22_lotac' ";
       $virgula = ",";
       if(trim($this->r22_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r22_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r22_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r22_instit"])){ 
       $sql  .= $virgula." r22_instit = $this->r22_instit ";
       $virgula = ",";
       if(trim($this->r22_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r22_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r22_anousu!=null){
       $sql .= " r22_anousu = $this->r22_anousu";
     }
     if($r22_mesusu!=null){
       $sql .= " and  r22_mesusu = $this->r22_mesusu";
     }
     if($r22_regist!=null){
       $sql .= " and  r22_regist = $this->r22_regist";
     }
     if($r22_rubric!=null){
       $sql .= " and  r22_rubric = '$this->r22_rubric'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r22_anousu,$this->r22_mesusu,$this->r22_regist,$this->r22_rubric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3941,'$this->r22_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3942,'$this->r22_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3943,'$this->r22_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,3944,'$this->r22_rubric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r22_anousu"]))
           $resac = db_query("insert into db_acount values($acount,553,3941,'".AddSlashes(pg_result($resaco,$conresaco,'r22_anousu'))."','$this->r22_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r22_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,553,3942,'".AddSlashes(pg_result($resaco,$conresaco,'r22_mesusu'))."','$this->r22_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r22_regist"]))
           $resac = db_query("insert into db_acount values($acount,553,3943,'".AddSlashes(pg_result($resaco,$conresaco,'r22_regist'))."','$this->r22_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r22_rubric"]))
           $resac = db_query("insert into db_acount values($acount,553,3944,'".AddSlashes(pg_result($resaco,$conresaco,'r22_rubric'))."','$this->r22_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r22_valor"]))
           $resac = db_query("insert into db_acount values($acount,553,3945,'".AddSlashes(pg_result($resaco,$conresaco,'r22_valor'))."','$this->r22_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r22_pd"]))
           $resac = db_query("insert into db_acount values($acount,553,3946,'".AddSlashes(pg_result($resaco,$conresaco,'r22_pd'))."','$this->r22_pd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r22_quant"]))
           $resac = db_query("insert into db_acount values($acount,553,3947,'".AddSlashes(pg_result($resaco,$conresaco,'r22_quant'))."','$this->r22_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r22_lotac"]))
           $resac = db_query("insert into db_acount values($acount,553,3948,'".AddSlashes(pg_result($resaco,$conresaco,'r22_lotac'))."','$this->r22_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r22_instit"]))
           $resac = db_query("insert into db_acount values($acount,553,7454,'".AddSlashes(pg_result($resaco,$conresaco,'r22_instit'))."','$this->r22_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calculo de Adiantamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r22_anousu."-".$this->r22_mesusu."-".$this->r22_regist."-".$this->r22_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Calculo de Adiantamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r22_anousu."-".$this->r22_mesusu."-".$this->r22_regist."-".$this->r22_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r22_anousu."-".$this->r22_mesusu."-".$this->r22_regist."-".$this->r22_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r22_anousu=null,$r22_mesusu=null,$r22_regist=null,$r22_rubric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r22_anousu,$r22_mesusu,$r22_regist,$r22_rubric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3941,'$r22_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3942,'$r22_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3943,'$r22_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,3944,'$r22_rubric','E')");
         $resac = db_query("insert into db_acount values($acount,553,3941,'','".AddSlashes(pg_result($resaco,$iresaco,'r22_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,553,3942,'','".AddSlashes(pg_result($resaco,$iresaco,'r22_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,553,3943,'','".AddSlashes(pg_result($resaco,$iresaco,'r22_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,553,3944,'','".AddSlashes(pg_result($resaco,$iresaco,'r22_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,553,3945,'','".AddSlashes(pg_result($resaco,$iresaco,'r22_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,553,3946,'','".AddSlashes(pg_result($resaco,$iresaco,'r22_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,553,3947,'','".AddSlashes(pg_result($resaco,$iresaco,'r22_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,553,3948,'','".AddSlashes(pg_result($resaco,$iresaco,'r22_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,553,7454,'','".AddSlashes(pg_result($resaco,$iresaco,'r22_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from gerfadi
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r22_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r22_anousu = $r22_anousu ";
        }
        if($r22_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r22_mesusu = $r22_mesusu ";
        }
        if($r22_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r22_regist = $r22_regist ";
        }
        if($r22_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r22_rubric = '$r22_rubric' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calculo de Adiantamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r22_anousu."-".$r22_mesusu."-".$r22_regist."-".$r22_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Calculo de Adiantamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r22_anousu."-".$r22_mesusu."-".$r22_regist."-".$r22_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r22_anousu."-".$r22_mesusu."-".$r22_regist."-".$r22_rubric;
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
        $this->erro_sql   = "Record Vazio na Tabela:gerfadi";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r22_anousu=null,$r22_mesusu=null,$r22_regist=null,$r22_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfadi ";
     $sql .= "      inner join db_config  on  db_config.codigo = gerfadi.r22_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = gerfadi.r22_anousu 
		                                   and  lotacao.r13_mesusu = gerfadi.r22_mesusu 
																			 and  lotacao.r13_codigo = gerfadi.r22_lotac
																			 and  lotacao.r13_instit = gerfadi.r22_instit ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = gerfadi.r22_anousu 
		                                   and  pessoal.r01_mesusu = gerfadi.r22_mesusu 
																			 and  pessoal.r01_regist = gerfadi.r22_regist
																			 and  pessoal.r01_instit = gerfadi.r22_instit ";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = gerfadi.r22_anousu 
		                                    and  rubricas.r06_mesusu = gerfadi.r22_mesusu 
																				and  rubricas.r06_codigo = gerfadi.r22_rubric
																				and  rubricas.r06_instit = gerfadi.r22_instit ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu 
		                                  and  funcao.r37_mesusu = pessoal.r01_mesusu 
																			and  funcao.r37_funcao = pessoal.r01_funcao ";
     $sql .= "      inner join inssirf  on  inssirf.r33_anousu = pessoal.r01_anousu 
		                                   and  inssirf.r33_mesusu = pessoal.r01_mesusu 
																			 and  inssirf.r33_codtab = pessoal.r01_tbprev
																			 and  inssirf.r33_instit = pessoal.r01_instit ";
     $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu 
		                                 and  cargo.r65_mesusu = pessoal.r01_mesusu 
																		 and  cargo.r65_cargo = pessoal.r01_cargo ";
     $sql2 = "";
     if($dbwhere==""){
       if($r22_anousu!=null ){
         $sql2 .= " where gerfadi.r22_anousu = $r22_anousu "; 
       } 
       if($r22_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfadi.r22_mesusu = $r22_mesusu "; 
       } 
       if($r22_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfadi.r22_regist = $r22_regist "; 
       } 
       if($r22_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfadi.r22_rubric = '$r22_rubric' "; 
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
   function sql_query_file ( $r22_anousu=null,$r22_mesusu=null,$r22_regist=null,$r22_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfadi ";
     $sql2 = "";
     if($dbwhere==""){
       if($r22_anousu!=null ){
         $sql2 .= " where gerfadi.r22_anousu = $r22_anousu "; 
       } 
       if($r22_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfadi.r22_mesusu = $r22_mesusu "; 
       } 
       if($r22_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfadi.r22_regist = $r22_regist "; 
       } 
       if($r22_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfadi.r22_rubric = '$r22_rubric' "; 
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
   function sql_query_rhrubricas ( $r22_anousu=null,$r22_mesusu=null,$r22_regist=null,$r22_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfadi ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = gerfadi.r22_rubric 
		                                       and rhrubricas.rh27_instit = gerfadi.r22_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($r22_anousu!=null ){
         $sql2 .= " where gerfadi.r22_anousu = $r22_anousu "; 
       } 
       if($r22_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfadi.r22_mesusu = $r22_mesusu "; 
       } 
       if($r22_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfadi.r22_regist = $r22_regist "; 
       } 
       if($r22_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfadi.r22_rubric = '$r22_rubric' "; 
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

  public function migraGerfAdi($iInstituicao) {
    
    $sSql  = "create table w_migracao_adiantamento as select distinct r21_anousu,                                                    ";
    $sSql .= "                                                    r21_mesusu,                                                        ";
    $sSql .= "                                                    r21_instit                                                         ";
    $sSql .= "                                      from pontofa                                                                     ";
    $sSql .= "                                      inner join gerfadi on r21_anousu = r22_anousu                                    ";
    $sSql .= "                                                        and r21_mesusu = r22_mesusu                                    ";
    $sSql .= "                                                        and r21_instit = {$iInstituicao};                              ";
    $sSql .= "                                                                                                                       ";
                                                                                                                                     
    $sSql .= "insert into rhfolhapagamento                                                                                           ";
    $sSql .= "select nextval('rhfolhapagamento_rh141_sequencial_seq'),                                                               ";
    $sSql .= "       0,                                                                                                              ";
    $sSql .= "       r21_anousu,                                                                                                     ";
    $sSql .= "       r21_mesusu,                                                                                                     ";
    $sSql .= "       r21_anousu,                                                                                                     ";
    $sSql .= "       r21_mesusu,                                                                                                     ";
    $sSql .= "       r21_instit,                                                                                                     ";
    $sSql .= "       4,                                                                                                              ";
    $sSql .= "       false,                                                                                                          ";
    $sSql .= "       'Folha Adiantamento número: 0 da competência: ' || r21_anousu || '/' || r21_mesusu || ' gerada automaticamente.'";
    $sSql .= "  from w_migracao_adiantamento                                                                                         ";
    $sSql .= "order by r21_anousu asc,                                                                                               ";
    $sSql .= "         r21_mesusu asc;                                                                                               ";

    return $sSql;         

  }

}
