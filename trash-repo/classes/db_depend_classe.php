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
//CLASSE DA ENTIDADE depend
class cl_depend { 
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
   var $r03_anousu = 0; 
   var $r03_mesusu = 0; 
   var $r03_regist = 0; 
   var $r03_nome = null; 
   var $r03_dtnasc_dia = null; 
   var $r03_dtnasc_mes = null; 
   var $r03_dtnasc_ano = null; 
   var $r03_dtnasc = null; 
   var $r03_lotac = null; 
   var $r03_gparen = null; 
   var $r03_depend = null; 
   var $r03_seq = 0; 
   var $r03_irf = null; 
   var $r03_especi = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r03_anousu = int4 = Ano do Exercicio 
                 r03_mesusu = int4 = Mes do Exercicio 
                 r03_regist = int4 = Codigo do Funcionario 
                 r03_nome = varchar(40) = Nome do Dependente 
                 r03_dtnasc = date = Data de Nascimento 
                 r03_lotac = varchar(4) = Lotação do Servidor 
                 r03_gparen = varchar(1) = Parentesco 
                 r03_depend = varchar(1) = Salário Família 
                 r03_seq = int4 = Sequencia dos Dependentes 
                 r03_irf = varchar(1) = IRF 
                 r03_especi = varchar(1) = Especial 
                 ";
   //funcao construtor da classe 
   function cl_depend() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("depend"); 
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
       $this->r03_anousu = ($this->r03_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_anousu"]:$this->r03_anousu);
       $this->r03_mesusu = ($this->r03_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_mesusu"]:$this->r03_mesusu);
       $this->r03_regist = ($this->r03_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_regist"]:$this->r03_regist);
       $this->r03_nome = ($this->r03_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_nome"]:$this->r03_nome);
       if($this->r03_dtnasc == ""){
         $this->r03_dtnasc_dia = ($this->r03_dtnasc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_dtnasc_dia"]:$this->r03_dtnasc_dia);
         $this->r03_dtnasc_mes = ($this->r03_dtnasc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_dtnasc_mes"]:$this->r03_dtnasc_mes);
         $this->r03_dtnasc_ano = ($this->r03_dtnasc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_dtnasc_ano"]:$this->r03_dtnasc_ano);
         if($this->r03_dtnasc_dia != ""){
            $this->r03_dtnasc = $this->r03_dtnasc_ano."-".$this->r03_dtnasc_mes."-".$this->r03_dtnasc_dia;
         }
       }
       $this->r03_lotac = ($this->r03_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_lotac"]:$this->r03_lotac);
       $this->r03_gparen = ($this->r03_gparen == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_gparen"]:$this->r03_gparen);
       $this->r03_depend = ($this->r03_depend == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_depend"]:$this->r03_depend);
       $this->r03_seq = ($this->r03_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_seq"]:$this->r03_seq);
       $this->r03_irf = ($this->r03_irf == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_irf"]:$this->r03_irf);
       $this->r03_especi = ($this->r03_especi == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_especi"]:$this->r03_especi);
     }else{
       $this->r03_anousu = ($this->r03_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_anousu"]:$this->r03_anousu);
       $this->r03_mesusu = ($this->r03_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_mesusu"]:$this->r03_mesusu);
       $this->r03_regist = ($this->r03_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_regist"]:$this->r03_regist);
       $this->r03_nome = ($this->r03_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["r03_nome"]:$this->r03_nome);
     }
   }
   // funcao para inclusao
   function incluir ($r03_anousu,$r03_mesusu,$r03_regist,$r03_nome){ 
      $this->atualizacampos();
     if($this->r03_dtnasc == null ){ 
       $this->erro_sql = " Campo Data de Nascimento nao Informado.";
       $this->erro_campo = "r03_dtnasc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r03_lotac == null ){ 
       $this->erro_sql = " Campo Lotação do Servidor nao Informado.";
       $this->erro_campo = "r03_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r03_gparen == null ){ 
       $this->erro_sql = " Campo Parentesco nao Informado.";
       $this->erro_campo = "r03_gparen";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r03_depend == null ){ 
       $this->erro_sql = " Campo Salário Família nao Informado.";
       $this->erro_campo = "r03_depend";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r03_seq == null ){ 
       $this->erro_sql = " Campo Sequencia dos Dependentes nao Informado.";
       $this->erro_campo = "r03_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r03_irf == null ){ 
       $this->erro_sql = " Campo IRF nao Informado.";
       $this->erro_campo = "r03_irf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r03_especi == null ){ 
       $this->erro_sql = " Campo Especial nao Informado.";
       $this->erro_campo = "r03_especi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r03_anousu = $r03_anousu; 
       $this->r03_mesusu = $r03_mesusu; 
       $this->r03_regist = $r03_regist; 
       $this->r03_nome = $r03_nome; 
     if(($this->r03_anousu == null) || ($this->r03_anousu == "") ){ 
       $this->erro_sql = " Campo r03_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r03_mesusu == null) || ($this->r03_mesusu == "") ){ 
       $this->erro_sql = " Campo r03_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r03_regist == null) || ($this->r03_regist == "") ){ 
       $this->erro_sql = " Campo r03_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r03_nome == null) || ($this->r03_nome == "") ){ 
       $this->erro_sql = " Campo r03_nome nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into depend(
                                       r03_anousu 
                                      ,r03_mesusu 
                                      ,r03_regist 
                                      ,r03_nome 
                                      ,r03_dtnasc 
                                      ,r03_lotac 
                                      ,r03_gparen 
                                      ,r03_depend 
                                      ,r03_seq 
                                      ,r03_irf 
                                      ,r03_especi 
                       )
                values (
                                $this->r03_anousu 
                               ,$this->r03_mesusu 
                               ,$this->r03_regist 
                               ,'$this->r03_nome' 
                               ,".($this->r03_dtnasc == "null" || $this->r03_dtnasc == ""?"null":"'".$this->r03_dtnasc."'")." 
                               ,'$this->r03_lotac' 
                               ,'$this->r03_gparen' 
                               ,'$this->r03_depend' 
                               ,$this->r03_seq 
                               ,'$this->r03_irf' 
                               ,'$this->r03_especi' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastra os Dependentes de cada Funcionario        ($this->r03_anousu."-".$this->r03_mesusu."-".$this->r03_regist."-".$this->r03_nome) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastra os Dependentes de cada Funcionario        já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastra os Dependentes de cada Funcionario        ($this->r03_anousu."-".$this->r03_mesusu."-".$this->r03_regist."-".$this->r03_nome) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r03_anousu."-".$this->r03_mesusu."-".$this->r03_regist."-".$this->r03_nome;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r03_anousu,$this->r03_mesusu,$this->r03_regist,$this->r03_nome));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3870,'$this->r03_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3871,'$this->r03_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3872,'$this->r03_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,3873,'$this->r03_nome','I')");
       $resac = db_query("insert into db_acount values($acount,544,3870,'','".AddSlashes(pg_result($resaco,0,'r03_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,544,3871,'','".AddSlashes(pg_result($resaco,0,'r03_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,544,3872,'','".AddSlashes(pg_result($resaco,0,'r03_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,544,3873,'','".AddSlashes(pg_result($resaco,0,'r03_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,544,3874,'','".AddSlashes(pg_result($resaco,0,'r03_dtnasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,544,3875,'','".AddSlashes(pg_result($resaco,0,'r03_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,544,3876,'','".AddSlashes(pg_result($resaco,0,'r03_gparen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,544,3877,'','".AddSlashes(pg_result($resaco,0,'r03_depend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,544,3878,'','".AddSlashes(pg_result($resaco,0,'r03_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,544,3879,'','".AddSlashes(pg_result($resaco,0,'r03_irf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,544,4594,'','".AddSlashes(pg_result($resaco,0,'r03_especi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r03_anousu=null,$r03_mesusu=null,$r03_regist=null,$r03_nome=null) { 
      $this->atualizacampos();
     $sql = " update depend set ";
     $virgula = "";
     if(trim($this->r03_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r03_anousu"])){ 
       $sql  .= $virgula." r03_anousu = $this->r03_anousu ";
       $virgula = ",";
       if(trim($this->r03_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r03_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r03_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r03_mesusu"])){ 
       $sql  .= $virgula." r03_mesusu = $this->r03_mesusu ";
       $virgula = ",";
       if(trim($this->r03_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r03_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r03_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r03_regist"])){ 
       $sql  .= $virgula." r03_regist = $this->r03_regist ";
       $virgula = ",";
       if(trim($this->r03_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r03_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r03_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r03_nome"])){ 
       $sql  .= $virgula." r03_nome = '$this->r03_nome' ";
       $virgula = ",";
       if(trim($this->r03_nome) == null ){ 
         $this->erro_sql = " Campo Nome do Dependente nao Informado.";
         $this->erro_campo = "r03_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r03_dtnasc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r03_dtnasc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r03_dtnasc_dia"] !="") ){ 
       $sql  .= $virgula." r03_dtnasc = '$this->r03_dtnasc' ";
       $virgula = ",";
       if(trim($this->r03_dtnasc) == null ){ 
         $this->erro_sql = " Campo Data de Nascimento nao Informado.";
         $this->erro_campo = "r03_dtnasc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r03_dtnasc_dia"])){ 
         $sql  .= $virgula." r03_dtnasc = null ";
         $virgula = ",";
         if(trim($this->r03_dtnasc) == null ){ 
           $this->erro_sql = " Campo Data de Nascimento nao Informado.";
           $this->erro_campo = "r03_dtnasc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r03_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r03_lotac"])){ 
       $sql  .= $virgula." r03_lotac = '$this->r03_lotac' ";
       $virgula = ",";
       if(trim($this->r03_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação do Servidor nao Informado.";
         $this->erro_campo = "r03_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r03_gparen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r03_gparen"])){ 
       $sql  .= $virgula." r03_gparen = '$this->r03_gparen' ";
       $virgula = ",";
       if(trim($this->r03_gparen) == null ){ 
         $this->erro_sql = " Campo Parentesco nao Informado.";
         $this->erro_campo = "r03_gparen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r03_depend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r03_depend"])){ 
       $sql  .= $virgula." r03_depend = '$this->r03_depend' ";
       $virgula = ",";
       if(trim($this->r03_depend) == null ){ 
         $this->erro_sql = " Campo Salário Família nao Informado.";
         $this->erro_campo = "r03_depend";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r03_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r03_seq"])){ 
       $sql  .= $virgula." r03_seq = $this->r03_seq ";
       $virgula = ",";
       if(trim($this->r03_seq) == null ){ 
         $this->erro_sql = " Campo Sequencia dos Dependentes nao Informado.";
         $this->erro_campo = "r03_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r03_irf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r03_irf"])){ 
       $sql  .= $virgula." r03_irf = '$this->r03_irf' ";
       $virgula = ",";
       if(trim($this->r03_irf) == null ){ 
         $this->erro_sql = " Campo IRF nao Informado.";
         $this->erro_campo = "r03_irf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r03_especi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r03_especi"])){ 
       $sql  .= $virgula." r03_especi = '$this->r03_especi' ";
       $virgula = ",";
       if(trim($this->r03_especi) == null ){ 
         $this->erro_sql = " Campo Especial nao Informado.";
         $this->erro_campo = "r03_especi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r03_anousu!=null){
       $sql .= " r03_anousu = $this->r03_anousu";
     }
     if($r03_mesusu!=null){
       $sql .= " and  r03_mesusu = $this->r03_mesusu";
     }
     if($r03_regist!=null){
       $sql .= " and  r03_regist = $this->r03_regist";
     }
     if($r03_nome!=null){
       $sql .= " and  r03_nome = '$this->r03_nome'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r03_anousu,$this->r03_mesusu,$this->r03_regist,$this->r03_nome));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3870,'$this->r03_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3871,'$this->r03_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3872,'$this->r03_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,3873,'$this->r03_nome','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r03_anousu"]))
           $resac = db_query("insert into db_acount values($acount,544,3870,'".AddSlashes(pg_result($resaco,$conresaco,'r03_anousu'))."','$this->r03_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r03_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,544,3871,'".AddSlashes(pg_result($resaco,$conresaco,'r03_mesusu'))."','$this->r03_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r03_regist"]))
           $resac = db_query("insert into db_acount values($acount,544,3872,'".AddSlashes(pg_result($resaco,$conresaco,'r03_regist'))."','$this->r03_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r03_nome"]))
           $resac = db_query("insert into db_acount values($acount,544,3873,'".AddSlashes(pg_result($resaco,$conresaco,'r03_nome'))."','$this->r03_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r03_dtnasc"]))
           $resac = db_query("insert into db_acount values($acount,544,3874,'".AddSlashes(pg_result($resaco,$conresaco,'r03_dtnasc'))."','$this->r03_dtnasc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r03_lotac"]))
           $resac = db_query("insert into db_acount values($acount,544,3875,'".AddSlashes(pg_result($resaco,$conresaco,'r03_lotac'))."','$this->r03_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r03_gparen"]))
           $resac = db_query("insert into db_acount values($acount,544,3876,'".AddSlashes(pg_result($resaco,$conresaco,'r03_gparen'))."','$this->r03_gparen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r03_depend"]))
           $resac = db_query("insert into db_acount values($acount,544,3877,'".AddSlashes(pg_result($resaco,$conresaco,'r03_depend'))."','$this->r03_depend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r03_seq"]))
           $resac = db_query("insert into db_acount values($acount,544,3878,'".AddSlashes(pg_result($resaco,$conresaco,'r03_seq'))."','$this->r03_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r03_irf"]))
           $resac = db_query("insert into db_acount values($acount,544,3879,'".AddSlashes(pg_result($resaco,$conresaco,'r03_irf'))."','$this->r03_irf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r03_especi"]))
           $resac = db_query("insert into db_acount values($acount,544,4594,'".AddSlashes(pg_result($resaco,$conresaco,'r03_especi'))."','$this->r03_especi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastra os Dependentes de cada Funcionario        nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r03_anousu."-".$this->r03_mesusu."-".$this->r03_regist."-".$this->r03_nome;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastra os Dependentes de cada Funcionario        nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r03_anousu."-".$this->r03_mesusu."-".$this->r03_regist."-".$this->r03_nome;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r03_anousu."-".$this->r03_mesusu."-".$this->r03_regist."-".$this->r03_nome;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r03_anousu=null,$r03_mesusu=null,$r03_regist=null,$r03_nome=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r03_anousu,$r03_mesusu,$r03_regist,$r03_nome));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3870,'$r03_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3871,'$r03_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3872,'$r03_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,3873,'$r03_nome','E')");
         $resac = db_query("insert into db_acount values($acount,544,3870,'','".AddSlashes(pg_result($resaco,$iresaco,'r03_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,544,3871,'','".AddSlashes(pg_result($resaco,$iresaco,'r03_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,544,3872,'','".AddSlashes(pg_result($resaco,$iresaco,'r03_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,544,3873,'','".AddSlashes(pg_result($resaco,$iresaco,'r03_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,544,3874,'','".AddSlashes(pg_result($resaco,$iresaco,'r03_dtnasc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,544,3875,'','".AddSlashes(pg_result($resaco,$iresaco,'r03_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,544,3876,'','".AddSlashes(pg_result($resaco,$iresaco,'r03_gparen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,544,3877,'','".AddSlashes(pg_result($resaco,$iresaco,'r03_depend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,544,3878,'','".AddSlashes(pg_result($resaco,$iresaco,'r03_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,544,3879,'','".AddSlashes(pg_result($resaco,$iresaco,'r03_irf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,544,4594,'','".AddSlashes(pg_result($resaco,$iresaco,'r03_especi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from depend
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r03_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r03_anousu = $r03_anousu ";
        }
        if($r03_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r03_mesusu = $r03_mesusu ";
        }
        if($r03_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r03_regist = $r03_regist ";
        }
        if($r03_nome != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r03_nome = '$r03_nome' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastra os Dependentes de cada Funcionario        nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r03_anousu."-".$r03_mesusu."-".$r03_regist."-".$r03_nome;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastra os Dependentes de cada Funcionario        nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r03_anousu."-".$r03_mesusu."-".$r03_regist."-".$r03_nome;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r03_anousu."-".$r03_mesusu."-".$r03_regist."-".$r03_nome;
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
        $this->erro_sql   = "Record Vazio na Tabela:depend";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r03_anousu=null,$r03_mesusu=null,$r03_regist=null,$r03_nome=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from depend ";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = depend.r03_anousu 
		                                   and  lotacao.r13_mesusu = depend.r03_mesusu 
																			 and  lotacao.r13_codigo = depend.r03_lotac 
																			 and  lotacao.r13_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = depend.r03_anousu 
		                                   and  pessoal.r01_mesusu = depend.r03_mesusu 
																			 and  pessoal.r01_regist = depend.r03_regist 
																			 and  pessoal.r01_instit = ".db_getsession("DB_instit")." ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu 
		                                  and  funcao.r37_mesusu = pessoal.r01_mesusu 
																			and  funcao.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu 
		                                 and  cargo.r65_mesusu = pessoal.r01_mesusu 
																		 and  cargo.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r03_anousu!=null ){
         $sql2 .= " where depend.r03_anousu = $r03_anousu "; 
       } 
       if($r03_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " depend.r03_mesusu = $r03_mesusu "; 
       } 
       if($r03_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " depend.r03_regist = $r03_regist "; 
       } 
       if($r03_nome!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " depend.r03_nome = '$r03_nome' "; 
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
   function sql_query_file ( $r03_anousu=null,$r03_mesusu=null,$r03_regist=null,$r03_nome=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from depend ";
     $sql2 = "";
     if($dbwhere==""){
       if($r03_anousu!=null ){
         $sql2 .= " where depend.r03_anousu = $r03_anousu "; 
       } 
       if($r03_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " depend.r03_mesusu = $r03_mesusu "; 
       } 
       if($r03_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " depend.r03_regist = $r03_regist "; 
       } 
       if($r03_nome!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " depend.r03_nome = '$r03_nome' "; 
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