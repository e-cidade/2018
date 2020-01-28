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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcppa
class cl_orcppa { 
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
   var $o23_codppa = 0; 
   var $o23_codleippa = 0; 
   var $o23_anoexe = 0; 
   var $o23_orgao = 0; 
   var $o23_unidade = 0; 
   var $o23_funcao = 0; 
   var $o23_subfuncao = 0; 
   var $o23_programa = 0; 
   var $o23_programatxt = null; 
   var $o23_acao = 0; 
   var $o23_acaotxt = null; 
   var $o23_produto = 0; 
   var $o23_unimed = null; 
   var $o23_indica = 0; 
   var $o23_sitinicial = null; 
   var $o23_sitfinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o23_codppa = int8 = Código 
                 o23_codleippa = int8 = Lei 
                 o23_anoexe = int4 = Exercicio 
                 o23_orgao = int4 = Órgão 
                 o23_unidade = int4 = Unidade 
                 o23_funcao = int4 = Função 
                 o23_subfuncao = int4 = Sub Função 
                 o23_programa = int4 = Programa 
                 o23_programatxt = text = Descrição programa 
                 o23_acao = int4 = Projetos / Atividades 
                 o23_acaotxt = text = Descrição da ação 
                 o23_produto = int8 = Produto 
                 o23_unimed = varchar(15) = Unidade medida 
                 o23_indica = int4 = Indicador 
                 o23_sitinicial = text = Sit. Inicial 
                 o23_sitfinal = text = Sit. Final 
                 ";
   //funcao construtor da classe 
   function cl_orcppa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcppa"); 
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
       $this->o23_codppa = ($this->o23_codppa == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_codppa"]:$this->o23_codppa);
       $this->o23_codleippa = ($this->o23_codleippa == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_codleippa"]:$this->o23_codleippa);
       $this->o23_anoexe = ($this->o23_anoexe == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_anoexe"]:$this->o23_anoexe);
       $this->o23_orgao = ($this->o23_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_orgao"]:$this->o23_orgao);
       $this->o23_unidade = ($this->o23_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_unidade"]:$this->o23_unidade);
       $this->o23_funcao = ($this->o23_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_funcao"]:$this->o23_funcao);
       $this->o23_subfuncao = ($this->o23_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_subfuncao"]:$this->o23_subfuncao);
       $this->o23_programa = ($this->o23_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_programa"]:$this->o23_programa);
       $this->o23_programatxt = ($this->o23_programatxt == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_programatxt"]:$this->o23_programatxt);
       $this->o23_acao = ($this->o23_acao == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_acao"]:$this->o23_acao);
       $this->o23_acaotxt = ($this->o23_acaotxt == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_acaotxt"]:$this->o23_acaotxt);
       $this->o23_produto = ($this->o23_produto == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_produto"]:$this->o23_produto);
       $this->o23_unimed = ($this->o23_unimed == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_unimed"]:$this->o23_unimed);
       $this->o23_indica = ($this->o23_indica == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_indica"]:$this->o23_indica);
       $this->o23_sitinicial = ($this->o23_sitinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_sitinicial"]:$this->o23_sitinicial);
       $this->o23_sitfinal = ($this->o23_sitfinal == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_sitfinal"]:$this->o23_sitfinal);
     }else{
       $this->o23_codppa = ($this->o23_codppa == ""?@$GLOBALS["HTTP_POST_VARS"]["o23_codppa"]:$this->o23_codppa);
     }
   }
   // funcao para inclusao
   function incluir ($o23_codppa){ 
      $this->atualizacampos();
     if($this->o23_codleippa == null ){ 
       $this->erro_sql = " Campo Lei nao Informado.";
       $this->erro_campo = "o23_codleippa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o23_anoexe == null ){ 
       $this->erro_sql = " Campo Exercicio nao Informado.";
       $this->erro_campo = "o23_anoexe";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o23_orgao == null ){ 
       $this->erro_sql = " Campo Órgão nao Informado.";
       $this->erro_campo = "o23_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o23_unidade == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "o23_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o23_funcao == null ){ 
       $this->erro_sql = " Campo Função nao Informado.";
       $this->erro_campo = "o23_funcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o23_subfuncao == null ){ 
       $this->erro_sql = " Campo Sub Função nao Informado.";
       $this->erro_campo = "o23_subfuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o23_programa == null ){ 
       $this->erro_sql = " Campo Programa nao Informado.";
       $this->erro_campo = "o23_programa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o23_acao == null ){ 
       $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
       $this->erro_campo = "o23_acao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o23_produto == null ){ 
       $this->erro_sql = " Campo Produto nao Informado.";
       $this->erro_campo = "o23_produto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o23_unimed == null ){ 
       $this->erro_sql = " Campo Unidade medida nao Informado.";
       $this->erro_campo = "o23_unimed";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o23_indica == null ){ 
       $this->erro_sql = " Campo Indicador nao Informado.";
       $this->erro_campo = "o23_indica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o23_sitinicial == null ){ 
       $this->erro_sql = " Campo Sit. Inicial nao Informado.";
       $this->erro_campo = "o23_sitinicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o23_sitfinal == null ){ 
       $this->erro_sql = " Campo Sit. Final nao Informado.";
       $this->erro_campo = "o23_sitfinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o23_codppa == "" || $o23_codppa == null ){
       $result = db_query("select nextval('orcppa_o23_codppa_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcppa_o23_codppa_seq do campo: o23_codppa"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o23_codppa = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcppa_o23_codppa_seq");
       if(($result != false) && (pg_result($result,0,0) < $o23_codppa)){
         $this->erro_sql = " Campo o23_codppa maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o23_codppa = $o23_codppa; 
       }
     }
     if(($this->o23_codppa == null) || ($this->o23_codppa == "") ){ 
       $this->erro_sql = " Campo o23_codppa nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcppa(
                                       o23_codppa 
                                      ,o23_codleippa 
                                      ,o23_anoexe 
                                      ,o23_orgao 
                                      ,o23_unidade 
                                      ,o23_funcao 
                                      ,o23_subfuncao 
                                      ,o23_programa 
                                      ,o23_programatxt 
                                      ,o23_acao 
                                      ,o23_acaotxt 
                                      ,o23_produto 
                                      ,o23_unimed 
                                      ,o23_indica 
                                      ,o23_sitinicial 
                                      ,o23_sitfinal 
                       )
                values (
                                $this->o23_codppa 
                               ,$this->o23_codleippa 
                               ,$this->o23_anoexe 
                               ,$this->o23_orgao 
                               ,$this->o23_unidade 
                               ,$this->o23_funcao 
                               ,$this->o23_subfuncao 
                               ,$this->o23_programa 
                               ,'$this->o23_programatxt' 
                               ,$this->o23_acao 
                               ,'$this->o23_acaotxt' 
                               ,$this->o23_produto 
                               ,'$this->o23_unimed' 
                               ,$this->o23_indica 
                               ,'$this->o23_sitinicial' 
                               ,'$this->o23_sitfinal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "PPA ($this->o23_codppa) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "PPA já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "PPA ($this->o23_codppa) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o23_codppa;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o23_codppa));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6473,'$this->o23_codppa','I')");
       $resac = db_query("insert into db_acount values($acount,1065,6473,'','".AddSlashes(pg_result($resaco,0,'o23_codppa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6507,'','".AddSlashes(pg_result($resaco,0,'o23_codleippa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6493,'','".AddSlashes(pg_result($resaco,0,'o23_anoexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6474,'','".AddSlashes(pg_result($resaco,0,'o23_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6475,'','".AddSlashes(pg_result($resaco,0,'o23_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6476,'','".AddSlashes(pg_result($resaco,0,'o23_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6477,'','".AddSlashes(pg_result($resaco,0,'o23_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6478,'','".AddSlashes(pg_result($resaco,0,'o23_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6479,'','".AddSlashes(pg_result($resaco,0,'o23_programatxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6480,'','".AddSlashes(pg_result($resaco,0,'o23_acao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6481,'','".AddSlashes(pg_result($resaco,0,'o23_acaotxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6482,'','".AddSlashes(pg_result($resaco,0,'o23_produto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6483,'','".AddSlashes(pg_result($resaco,0,'o23_unimed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6862,'','".AddSlashes(pg_result($resaco,0,'o23_indica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6863,'','".AddSlashes(pg_result($resaco,0,'o23_sitinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1065,6864,'','".AddSlashes(pg_result($resaco,0,'o23_sitfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o23_codppa=null) { 
      $this->atualizacampos();
     $sql = " update orcppa set ";
     $virgula = "";
     if(trim($this->o23_codppa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_codppa"])){ 
       $sql  .= $virgula." o23_codppa = $this->o23_codppa ";
       $virgula = ",";
       if(trim($this->o23_codppa) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "o23_codppa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_codleippa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_codleippa"])){ 
       $sql  .= $virgula." o23_codleippa = $this->o23_codleippa ";
       $virgula = ",";
       if(trim($this->o23_codleippa) == null ){ 
         $this->erro_sql = " Campo Lei nao Informado.";
         $this->erro_campo = "o23_codleippa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_anoexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_anoexe"])){ 
       $sql  .= $virgula." o23_anoexe = $this->o23_anoexe ";
       $virgula = ",";
       if(trim($this->o23_anoexe) == null ){ 
         $this->erro_sql = " Campo Exercicio nao Informado.";
         $this->erro_campo = "o23_anoexe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_orgao"])){ 
       $sql  .= $virgula." o23_orgao = $this->o23_orgao ";
       $virgula = ",";
       if(trim($this->o23_orgao) == null ){ 
         $this->erro_sql = " Campo Órgão nao Informado.";
         $this->erro_campo = "o23_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_unidade"])){ 
       $sql  .= $virgula." o23_unidade = $this->o23_unidade ";
       $virgula = ",";
       if(trim($this->o23_unidade) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "o23_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_funcao"])){ 
       $sql  .= $virgula." o23_funcao = $this->o23_funcao ";
       $virgula = ",";
       if(trim($this->o23_funcao) == null ){ 
         $this->erro_sql = " Campo Função nao Informado.";
         $this->erro_campo = "o23_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_subfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_subfuncao"])){ 
       $sql  .= $virgula." o23_subfuncao = $this->o23_subfuncao ";
       $virgula = ",";
       if(trim($this->o23_subfuncao) == null ){ 
         $this->erro_sql = " Campo Sub Função nao Informado.";
         $this->erro_campo = "o23_subfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_programa"])){ 
       $sql  .= $virgula." o23_programa = $this->o23_programa ";
       $virgula = ",";
       if(trim($this->o23_programa) == null ){ 
         $this->erro_sql = " Campo Programa nao Informado.";
         $this->erro_campo = "o23_programa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_programatxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_programatxt"])){ 
       $sql  .= $virgula." o23_programatxt = '$this->o23_programatxt' ";
       $virgula = ",";
     }
     if(trim($this->o23_acao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_acao"])){ 
       $sql  .= $virgula." o23_acao = $this->o23_acao ";
       $virgula = ",";
       if(trim($this->o23_acao) == null ){ 
         $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
         $this->erro_campo = "o23_acao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_acaotxt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_acaotxt"])){ 
       $sql  .= $virgula." o23_acaotxt = '$this->o23_acaotxt' ";
       $virgula = ",";
     }
     if(trim($this->o23_produto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_produto"])){ 
       $sql  .= $virgula." o23_produto = $this->o23_produto ";
       $virgula = ",";
       if(trim($this->o23_produto) == null ){ 
         $this->erro_sql = " Campo Produto nao Informado.";
         $this->erro_campo = "o23_produto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_unimed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_unimed"])){ 
       $sql  .= $virgula." o23_unimed = '$this->o23_unimed' ";
       $virgula = ",";
       if(trim($this->o23_unimed) == null ){ 
         $this->erro_sql = " Campo Unidade medida nao Informado.";
         $this->erro_campo = "o23_unimed";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_indica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_indica"])){ 
       $sql  .= $virgula." o23_indica = $this->o23_indica ";
       $virgula = ",";
       if(trim($this->o23_indica) == null ){ 
         $this->erro_sql = " Campo Indicador nao Informado.";
         $this->erro_campo = "o23_indica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_sitinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_sitinicial"])){ 
       $sql  .= $virgula." o23_sitinicial = '$this->o23_sitinicial' ";
       $virgula = ",";
       if(trim($this->o23_sitinicial) == null ){ 
         $this->erro_sql = " Campo Sit. Inicial nao Informado.";
         $this->erro_campo = "o23_sitinicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o23_sitfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o23_sitfinal"])){ 
       $sql  .= $virgula." o23_sitfinal = '$this->o23_sitfinal' ";
       $virgula = ",";
       if(trim($this->o23_sitfinal) == null ){ 
         $this->erro_sql = " Campo Sit. Final nao Informado.";
         $this->erro_campo = "o23_sitfinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o23_codppa!=null){
       $sql .= " o23_codppa = $this->o23_codppa";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o23_codppa));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6473,'$this->o23_codppa','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_codppa"]))
           $resac = db_query("insert into db_acount values($acount,1065,6473,'".AddSlashes(pg_result($resaco,$conresaco,'o23_codppa'))."','$this->o23_codppa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_codleippa"]))
           $resac = db_query("insert into db_acount values($acount,1065,6507,'".AddSlashes(pg_result($resaco,$conresaco,'o23_codleippa'))."','$this->o23_codleippa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_anoexe"]))
           $resac = db_query("insert into db_acount values($acount,1065,6493,'".AddSlashes(pg_result($resaco,$conresaco,'o23_anoexe'))."','$this->o23_anoexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_orgao"]))
           $resac = db_query("insert into db_acount values($acount,1065,6474,'".AddSlashes(pg_result($resaco,$conresaco,'o23_orgao'))."','$this->o23_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_unidade"]))
           $resac = db_query("insert into db_acount values($acount,1065,6475,'".AddSlashes(pg_result($resaco,$conresaco,'o23_unidade'))."','$this->o23_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_funcao"]))
           $resac = db_query("insert into db_acount values($acount,1065,6476,'".AddSlashes(pg_result($resaco,$conresaco,'o23_funcao'))."','$this->o23_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_subfuncao"]))
           $resac = db_query("insert into db_acount values($acount,1065,6477,'".AddSlashes(pg_result($resaco,$conresaco,'o23_subfuncao'))."','$this->o23_subfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_programa"]))
           $resac = db_query("insert into db_acount values($acount,1065,6478,'".AddSlashes(pg_result($resaco,$conresaco,'o23_programa'))."','$this->o23_programa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_programatxt"]))
           $resac = db_query("insert into db_acount values($acount,1065,6479,'".AddSlashes(pg_result($resaco,$conresaco,'o23_programatxt'))."','$this->o23_programatxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_acao"]))
           $resac = db_query("insert into db_acount values($acount,1065,6480,'".AddSlashes(pg_result($resaco,$conresaco,'o23_acao'))."','$this->o23_acao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_acaotxt"]))
           $resac = db_query("insert into db_acount values($acount,1065,6481,'".AddSlashes(pg_result($resaco,$conresaco,'o23_acaotxt'))."','$this->o23_acaotxt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_produto"]))
           $resac = db_query("insert into db_acount values($acount,1065,6482,'".AddSlashes(pg_result($resaco,$conresaco,'o23_produto'))."','$this->o23_produto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_unimed"]))
           $resac = db_query("insert into db_acount values($acount,1065,6483,'".AddSlashes(pg_result($resaco,$conresaco,'o23_unimed'))."','$this->o23_unimed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_indica"]))
           $resac = db_query("insert into db_acount values($acount,1065,6862,'".AddSlashes(pg_result($resaco,$conresaco,'o23_indica'))."','$this->o23_indica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_sitinicial"]))
           $resac = db_query("insert into db_acount values($acount,1065,6863,'".AddSlashes(pg_result($resaco,$conresaco,'o23_sitinicial'))."','$this->o23_sitinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o23_sitfinal"]))
           $resac = db_query("insert into db_acount values($acount,1065,6864,'".AddSlashes(pg_result($resaco,$conresaco,'o23_sitfinal'))."','$this->o23_sitfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "PPA nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o23_codppa;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "PPA nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o23_codppa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o23_codppa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o23_codppa=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o23_codppa));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6473,'$o23_codppa','E')");
         $resac = db_query("insert into db_acount values($acount,1065,6473,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_codppa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6507,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_codleippa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6493,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_anoexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6474,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6475,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6476,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6477,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6478,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6479,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_programatxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6480,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_acao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6481,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_acaotxt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6482,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_produto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6483,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_unimed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6862,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_indica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6863,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_sitinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1065,6864,'','".AddSlashes(pg_result($resaco,$iresaco,'o23_sitfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcppa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o23_codppa != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o23_codppa = $o23_codppa ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "PPA nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o23_codppa;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "PPA nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o23_codppa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o23_codppa;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcppa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   function sql_query ( $o23_codppa=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcppa ";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcppa.o23_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcppa.o23_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcppa.o23_anoexe and  orcprograma.o54_programa = orcppa.o23_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcppa.o23_anoexe and  orcprojativ.o55_projativ = orcppa.o23_acao";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcppa.o23_anoexe and  orcorgao.o40_orgao = orcppa.o23_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcppa.o23_anoexe and  orcunidade.o41_orgao = orcppa.o23_orgao and  orcunidade.o41_unidade = orcppa.o23_unidade";
     $sql .= "      inner join orcppalei  on  orcppalei.o21_codleippa = orcppa.o23_codleippa";
     $sql .= "      inner join orcindica  on  orcindica.o10_indica = orcppa.o23_indica";
     $sql .= "      inner join db_config  on  db_config.codigo = orcprograma.o54_instit";
    // $sql .= "      inner join db_config  as a on   a.codigo = orcprograma.o54_instit";
     $sql .= "      inner join db_config  as b on   b.codigo = orcprojativ.o55_instit";
    // $sql .= "      inner join db_config  as c on   c.codigo = orcprojativ.o55_instit";
     $sql .= "      inner join db_config  as d on   d.codigo = orcorgao.o40_instit";
    // $sql .= "      inner join db_config  as d on   d.codigo = orcorgao.o40_instit";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcunidade.o41_anousu and   d.o40_orgao = orcunidade.o41_orgao";
    // $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcunidade.o41_anousu and   d.o40_orgao = orcunidade.o41_orgao";
    // $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcunidade.o41_anousu and   d.o40_orgao = orcunidade.o41_orgao";
     $sql2 = "";
     if($dbwhere==""){
       if($o23_codppa!=null ){
         $sql2 .= " where orcppa.o23_codppa = $o23_codppa "; 
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
   function sql_query_file ( $o23_codppa=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcppa ";
     $sql2 = "";
     if($dbwhere==""){
       if($o23_codppa!=null ){
         $sql2 .= " where orcppa.o23_codppa = $o23_codppa "; 
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
   function sql_query_compl ( $o23_codppa=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from orcppa ";
     $sql .= "      left  join orcfuncao  on  orcfuncao.o52_funcao = orcppa.o23_funcao";
     $sql .= "      left join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcppa.o23_subfuncao";
     $sql .= "      left join orcprograma  on  orcprograma.o54_anousu = orcppa.o23_anoexe and  orcprograma.o54_programa = orcppa.o23_programa";
     $sql .= "      left join orcprojativ  on  orcprojativ.o55_anousu = orcppa.o23_anoexe and  orcprojativ.o55_projativ = orcppa.o23_acao";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcppa.o23_anoexe and  orcorgao.o40_orgao = orcppa.o23_orgao";
     $sql .= "      left join orcunidade  on  orcunidade.o41_anousu = orcppa.o23_anoexe and  orcunidade.o41_orgao = orcppa.o23_orgao and  orcunidade.o41_unidade = orcppa.o23_unidade";
     $sql .= "      inner join db_config  as d on   d.codigo = orcorgao.o40_instit";
     $sql .= "      left join orcproduto   on   o22_codproduto = o23_produto";
     $sql .= "      inner join orcppalei   on   o21_codleippa = o23_codleippa";
     $sql .= "      left  join orcindica   on   o10_indica = o23_indica";
     $sql2 = "";
     if($dbwhere==""){
       if($o23_codppa!=null ){
         $sql2 .= " where orcppa.o23_codppa = $o23_codppa ";
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
  /*
   *  metodo usado para selecionar direto e inserir no orcdotação 
   *  -----------------------------------------------------------------------------
   * 
   */
   function sql_query_exporta ($exercicio="",$o23_codppa=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcppa ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcppa.o23_anoexe and  orcorgao.o40_orgao = orcppa.o23_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcppa.o23_anoexe 
		                       				 and  orcunidade.o41_orgao = orcppa.o23_orgao 
                       		 				and  orcunidade.o41_unidade = orcppa.o23_unidade";     
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcppa.o23_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcppa.o23_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcppa.o23_anoexe 
                                                         and  orcprograma.o54_programa = orcppa.o23_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcppa.o23_anoexe 
                                                         and  orcprojativ.o55_projativ = orcppa.o23_acao";
     $sql .= "      inner join orcppaval on o24_codppa  = orcppa.o23_codppa 
                                                               and orcppaval.o24_exercicio  =  ".$exercicio;                                                         
     $sql .= "      inner join orcppatiporec  on o26_codseqppa = orcppaval.o24_codseqppa";
     $sql .= "      inner join orcppavalele on o25_codseqppa = orcppaval.o24_codseqppa "; 
      
     $sql .= "      inner join orcppalei  on  orcppalei.o21_codleippa = orcppa.o23_codleippa";

     $sql2 = "";
     if($dbwhere==""){
       if($o23_codppa!=null ){
         $sql2 .= " where orcppa.o23_codppa = $o23_codppa "; 
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