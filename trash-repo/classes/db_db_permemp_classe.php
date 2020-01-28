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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_permemp
class cl_db_permemp { 
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
   var $db20_codperm = 0; 
   var $db20_anousu = 0; 
   var $db20_orgao = 0; 
   var $db20_unidade = 0; 
   var $db20_funcao = 0; 
   var $db20_subfuncao = 0; 
   var $db20_programa = 0; 
   var $db20_projativ = 0; 
   var $db20_codele = 0; 
   var $db20_codigo = 0; 
   var $db20_tipoperm = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db20_codperm = int4 = Código Permissão 
                 db20_anousu = int4 = Exercício 
                 db20_orgao = int4 = Código Orgão 
                 db20_unidade = int4 = Código Unidade 
                 db20_funcao = int4 = Código da Função 
                 db20_subfuncao = int4 = Sub Função 
                 db20_programa = int4 = Programas Orçamento 
                 db20_projativ = int4 = Projetos / Atividades 
                 db20_codele = int4 = Código Elemento 
                 db20_codigo = int4 = Codigo do Tipo de Recurso 
                 db20_tipoperm = char(1) = Tipo de Permissão 
                 ";
   //funcao construtor da classe 
   function cl_db_permemp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_permemp"); 
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
       $this->db20_codperm = ($this->db20_codperm == ""?@$GLOBALS["HTTP_POST_VARS"]["db20_codperm"]:$this->db20_codperm);
       $this->db20_anousu = ($this->db20_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["db20_anousu"]:$this->db20_anousu);
       $this->db20_orgao = ($this->db20_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["db20_orgao"]:$this->db20_orgao);
       $this->db20_unidade = ($this->db20_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["db20_unidade"]:$this->db20_unidade);
       $this->db20_funcao = ($this->db20_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["db20_funcao"]:$this->db20_funcao);
       $this->db20_subfuncao = ($this->db20_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["db20_subfuncao"]:$this->db20_subfuncao);
       $this->db20_programa = ($this->db20_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["db20_programa"]:$this->db20_programa);
       $this->db20_projativ = ($this->db20_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["db20_projativ"]:$this->db20_projativ);
       $this->db20_codele = ($this->db20_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["db20_codele"]:$this->db20_codele);
       $this->db20_codigo = ($this->db20_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db20_codigo"]:$this->db20_codigo);
       $this->db20_tipoperm = ($this->db20_tipoperm == ""?@$GLOBALS["HTTP_POST_VARS"]["db20_tipoperm"]:$this->db20_tipoperm);
     }else{
       $this->db20_codperm = ($this->db20_codperm == ""?@$GLOBALS["HTTP_POST_VARS"]["db20_codperm"]:$this->db20_codperm);
     }
   }
   // funcao para inclusao
   function incluir ($db20_codperm){ 
      $this->atualizacampos();
     if($this->db20_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "db20_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db20_orgao == null ){ 
       $this->erro_sql = " Campo Código Orgão nao Informado.";
       $this->erro_campo = "db20_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db20_unidade == null ){ 
       $this->erro_sql = " Campo Código Unidade nao Informado.";
       $this->erro_campo = "db20_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db20_funcao == null ){ 
       $this->erro_sql = " Campo Código da Função nao Informado.";
       $this->erro_campo = "db20_funcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db20_subfuncao == null ){ 
       $this->erro_sql = " Campo Sub Função nao Informado.";
       $this->erro_campo = "db20_subfuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db20_programa == null ){ 
       $this->erro_sql = " Campo Programas Orçamento nao Informado.";
       $this->erro_campo = "db20_programa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db20_projativ == null ){ 
       $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
       $this->erro_campo = "db20_projativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db20_codele == null ){ 
       $this->erro_sql = " Campo Código Elemento nao Informado.";
       $this->erro_campo = "db20_codele";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db20_codigo == null ){ 
       $this->erro_sql = " Campo Codigo do Tipo de Recurso nao Informado.";
       $this->erro_campo = "db20_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db20_tipoperm == null ){ 
       $this->erro_sql = " Campo Tipo de Permissão nao Informado.";
       $this->erro_campo = "db20_tipoperm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db20_codperm == "" || $db20_codperm == null ){
       $result = db_query("select nextval('db_permemp_db20_codperm_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_permemp_db20_codperm_seq do campo: db20_codperm"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db20_codperm = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_permemp_db20_codperm_seq");
       if(($result != false) && (pg_result($result,0,0) < $db20_codperm)){
         $this->erro_sql = " Campo db20_codperm maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db20_codperm = $db20_codperm; 
       }
     }
     if(($this->db20_codperm == null) || ($this->db20_codperm == "") ){ 
       $this->erro_sql = " Campo db20_codperm nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_permemp(
                                       db20_codperm 
                                      ,db20_anousu 
                                      ,db20_orgao 
                                      ,db20_unidade 
                                      ,db20_funcao 
                                      ,db20_subfuncao 
                                      ,db20_programa 
                                      ,db20_projativ 
                                      ,db20_codele 
                                      ,db20_codigo 
                                      ,db20_tipoperm 
                       )
                values (
                                $this->db20_codperm 
                               ,$this->db20_anousu 
                               ,$this->db20_orgao 
                               ,$this->db20_unidade 
                               ,$this->db20_funcao 
                               ,$this->db20_subfuncao 
                               ,$this->db20_programa 
                               ,$this->db20_projativ 
                               ,$this->db20_codele 
                               ,$this->db20_codigo 
                               ,'$this->db20_tipoperm' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Permissão para Empenho ($this->db20_codperm) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Permissão para Empenho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Permissão para Empenho ($this->db20_codperm) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db20_codperm;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db20_codperm));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5568,'$this->db20_codperm','I')");
       $resac = db_query("insert into db_acount values($acount,883,5568,'','".AddSlashes(pg_result($resaco,0,'db20_codperm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,883,5569,'','".AddSlashes(pg_result($resaco,0,'db20_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,883,5570,'','".AddSlashes(pg_result($resaco,0,'db20_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,883,5571,'','".AddSlashes(pg_result($resaco,0,'db20_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,883,5572,'','".AddSlashes(pg_result($resaco,0,'db20_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,883,5573,'','".AddSlashes(pg_result($resaco,0,'db20_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,883,5574,'','".AddSlashes(pg_result($resaco,0,'db20_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,883,5575,'','".AddSlashes(pg_result($resaco,0,'db20_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,883,5576,'','".AddSlashes(pg_result($resaco,0,'db20_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,883,5577,'','".AddSlashes(pg_result($resaco,0,'db20_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,883,9830,'','".AddSlashes(pg_result($resaco,0,'db20_tipoperm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db20_codperm=null) { 
      $this->atualizacampos();
     $sql = " update db_permemp set ";
     $virgula = "";
     if(trim($this->db20_codperm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db20_codperm"])){ 
       $sql  .= $virgula." db20_codperm = $this->db20_codperm ";
       $virgula = ",";
       if(trim($this->db20_codperm) == null ){ 
         $this->erro_sql = " Campo Código Permissão nao Informado.";
         $this->erro_campo = "db20_codperm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db20_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db20_anousu"])){ 
       $sql  .= $virgula." db20_anousu = $this->db20_anousu ";
       $virgula = ",";
       if(trim($this->db20_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "db20_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db20_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db20_orgao"])){ 
       $sql  .= $virgula." db20_orgao = $this->db20_orgao ";
       $virgula = ",";
       if(trim($this->db20_orgao) == null ){ 
         $this->erro_sql = " Campo Código Orgão nao Informado.";
         $this->erro_campo = "db20_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db20_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db20_unidade"])){ 
       $sql  .= $virgula." db20_unidade = $this->db20_unidade ";
       $virgula = ",";
       if(trim($this->db20_unidade) == null ){ 
         $this->erro_sql = " Campo Código Unidade nao Informado.";
         $this->erro_campo = "db20_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db20_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db20_funcao"])){ 
       $sql  .= $virgula." db20_funcao = $this->db20_funcao ";
       $virgula = ",";
       if(trim($this->db20_funcao) == null ){ 
         $this->erro_sql = " Campo Código da Função nao Informado.";
         $this->erro_campo = "db20_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db20_subfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db20_subfuncao"])){ 
       $sql  .= $virgula." db20_subfuncao = $this->db20_subfuncao ";
       $virgula = ",";
       if(trim($this->db20_subfuncao) == null ){ 
         $this->erro_sql = " Campo Sub Função nao Informado.";
         $this->erro_campo = "db20_subfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db20_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db20_programa"])){ 
       $sql  .= $virgula." db20_programa = $this->db20_programa ";
       $virgula = ",";
       if(trim($this->db20_programa) == null ){ 
         $this->erro_sql = " Campo Programas Orçamento nao Informado.";
         $this->erro_campo = "db20_programa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db20_projativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db20_projativ"])){ 
       $sql  .= $virgula." db20_projativ = $this->db20_projativ ";
       $virgula = ",";
       if(trim($this->db20_projativ) == null ){ 
         $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
         $this->erro_campo = "db20_projativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db20_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db20_codele"])){ 
       $sql  .= $virgula." db20_codele = $this->db20_codele ";
       $virgula = ",";
       if(trim($this->db20_codele) == null ){ 
         $this->erro_sql = " Campo Código Elemento nao Informado.";
         $this->erro_campo = "db20_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db20_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db20_codigo"])){ 
       $sql  .= $virgula." db20_codigo = $this->db20_codigo ";
       $virgula = ",";
       if(trim($this->db20_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo do Tipo de Recurso nao Informado.";
         $this->erro_campo = "db20_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db20_tipoperm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db20_tipoperm"])){ 
       $sql  .= $virgula." db20_tipoperm = '$this->db20_tipoperm' ";
       $virgula = ",";
       if(trim($this->db20_tipoperm) == null ){ 
         $this->erro_sql = " Campo Tipo de Permissão nao Informado.";
         $this->erro_campo = "db20_tipoperm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db20_codperm!=null){
       $sql .= " db20_codperm = $this->db20_codperm";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db20_codperm));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5568,'$this->db20_codperm','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db20_codperm"]))
           $resac = db_query("insert into db_acount values($acount,883,5568,'".AddSlashes(pg_result($resaco,$conresaco,'db20_codperm'))."','$this->db20_codperm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db20_anousu"]))
           $resac = db_query("insert into db_acount values($acount,883,5569,'".AddSlashes(pg_result($resaco,$conresaco,'db20_anousu'))."','$this->db20_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db20_orgao"]))
           $resac = db_query("insert into db_acount values($acount,883,5570,'".AddSlashes(pg_result($resaco,$conresaco,'db20_orgao'))."','$this->db20_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db20_unidade"]))
           $resac = db_query("insert into db_acount values($acount,883,5571,'".AddSlashes(pg_result($resaco,$conresaco,'db20_unidade'))."','$this->db20_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db20_funcao"]))
           $resac = db_query("insert into db_acount values($acount,883,5572,'".AddSlashes(pg_result($resaco,$conresaco,'db20_funcao'))."','$this->db20_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db20_subfuncao"]))
           $resac = db_query("insert into db_acount values($acount,883,5573,'".AddSlashes(pg_result($resaco,$conresaco,'db20_subfuncao'))."','$this->db20_subfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db20_programa"]))
           $resac = db_query("insert into db_acount values($acount,883,5574,'".AddSlashes(pg_result($resaco,$conresaco,'db20_programa'))."','$this->db20_programa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db20_projativ"]))
           $resac = db_query("insert into db_acount values($acount,883,5575,'".AddSlashes(pg_result($resaco,$conresaco,'db20_projativ'))."','$this->db20_projativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db20_codele"]))
           $resac = db_query("insert into db_acount values($acount,883,5576,'".AddSlashes(pg_result($resaco,$conresaco,'db20_codele'))."','$this->db20_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db20_codigo"]))
           $resac = db_query("insert into db_acount values($acount,883,5577,'".AddSlashes(pg_result($resaco,$conresaco,'db20_codigo'))."','$this->db20_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db20_tipoperm"]))
           $resac = db_query("insert into db_acount values($acount,883,9830,'".AddSlashes(pg_result($resaco,$conresaco,'db20_tipoperm'))."','$this->db20_tipoperm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Permissão para Empenho nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db20_codperm;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Permissão para Empenho nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db20_codperm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db20_codperm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db20_codperm=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db20_codperm));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5568,'$db20_codperm','E')");
         $resac = db_query("insert into db_acount values($acount,883,5568,'','".AddSlashes(pg_result($resaco,$iresaco,'db20_codperm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,883,5569,'','".AddSlashes(pg_result($resaco,$iresaco,'db20_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,883,5570,'','".AddSlashes(pg_result($resaco,$iresaco,'db20_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,883,5571,'','".AddSlashes(pg_result($resaco,$iresaco,'db20_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,883,5572,'','".AddSlashes(pg_result($resaco,$iresaco,'db20_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,883,5573,'','".AddSlashes(pg_result($resaco,$iresaco,'db20_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,883,5574,'','".AddSlashes(pg_result($resaco,$iresaco,'db20_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,883,5575,'','".AddSlashes(pg_result($resaco,$iresaco,'db20_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,883,5576,'','".AddSlashes(pg_result($resaco,$iresaco,'db20_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,883,5577,'','".AddSlashes(pg_result($resaco,$iresaco,'db20_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,883,9830,'','".AddSlashes(pg_result($resaco,$iresaco,'db20_tipoperm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_permemp
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db20_codperm != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db20_codperm = $db20_codperm ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Permissão para Empenho nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db20_codperm;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Permissão para Empenho nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db20_codperm;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db20_codperm;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_permemp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db20_codperm=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_permemp ";
     $sql2 = "";
     if($dbwhere==""){
       if($db20_codperm!=null ){
         $sql2 .= " where db_permemp.db20_codperm = $db20_codperm "; 
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
   function sql_query_file ( $db20_codperm=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_permemp ";
     $sql2 = "";
     if($dbwhere==""){
       if($db20_codperm!=null ){
         $sql2 .= " where db_permemp.db20_codperm = $db20_codperm "; 
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
   function sql_query_origem ( $db20_codperm=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_permemp ";
     $sql .= "       left outer join db_usupermemp  on  db_usupermemp.db21_codperm = db_permemp.db20_codperm";
     $sql .= "       left outer join db_depusuemp  on  db_depusuemp.db22_codperm = db_permemp.db20_codperm";
     $sql2 = "";
     if($dbwhere==""){
       if($db20_codperm!=null ){
         $sql2 .= " where db_permemp.db20_codperm = $db20_codperm ";
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