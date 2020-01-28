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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcdotacao
class cl_orcdotacao { 
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
   var $o58_anousu = 0; 
   var $o58_coddot = 0; 
   var $o58_orgao = 0; 
   var $o58_unidade = 0; 
   var $o58_funcao = 0; 
   var $o58_subfuncao = 0; 
   var $o58_programa = 0; 
   var $o58_projativ = 0; 
   var $o58_codele = 0; 
   var $o58_codigo = 0; 
   var $o58_valor = 0; 
   var $o58_instit = 0; 
   var $o58_localizadorgastos = 0; 
   var $o58_datacriacao_dia = null; 
   var $o58_datacriacao_mes = null; 
   var $o58_datacriacao_ano = null; 
   var $o58_datacriacao = null; 
   var $o58_concarpeculiar = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o58_anousu = int4 = Exercício 
                 o58_coddot = int4 = Reduzido 
                 o58_orgao = int4 = Código Orgão 
                 o58_unidade = int4 = Código Unidade 
                 o58_funcao = int4 = Código da Função 
                 o58_subfuncao = int4 = Sub Função 
                 o58_programa = int4 = Programas Orçamento 
                 o58_projativ = int4 = Projetos / Atividades 
                 o58_codele = int4 = Código Elemento 
                 o58_codigo = int4 = Tipo de Recurso 
                 o58_valor = float8 = Previsão 
                 o58_instit = int4 = Instituição 
                 o58_localizadorgastos = int4 = Localizador dos Gastos 
                 o58_datacriacao = date = Data da Criação 
                 o58_concarpeculiar = varchar(100) = C.Peculiar/ C. Aplicação 
                 ";
   //funcao construtor da classe 
   function cl_orcdotacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcdotacao"); 
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
       $this->o58_anousu = ($this->o58_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_anousu"]:$this->o58_anousu);
       $this->o58_coddot = ($this->o58_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_coddot"]:$this->o58_coddot);
       $this->o58_orgao = ($this->o58_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_orgao"]:$this->o58_orgao);
       $this->o58_unidade = ($this->o58_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_unidade"]:$this->o58_unidade);
       $this->o58_funcao = ($this->o58_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_funcao"]:$this->o58_funcao);
       $this->o58_subfuncao = ($this->o58_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_subfuncao"]:$this->o58_subfuncao);
       $this->o58_programa = ($this->o58_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_programa"]:$this->o58_programa);
       $this->o58_projativ = ($this->o58_projativ == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_projativ"]:$this->o58_projativ);
       $this->o58_codele = ($this->o58_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_codele"]:$this->o58_codele);
       $this->o58_codigo = ($this->o58_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_codigo"]:$this->o58_codigo);
       $this->o58_valor = ($this->o58_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_valor"]:$this->o58_valor);
       $this->o58_instit = ($this->o58_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_instit"]:$this->o58_instit);
       $this->o58_localizadorgastos = ($this->o58_localizadorgastos == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_localizadorgastos"]:$this->o58_localizadorgastos);
       if($this->o58_datacriacao == ""){
         $this->o58_datacriacao_dia = ($this->o58_datacriacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_datacriacao_dia"]:$this->o58_datacriacao_dia);
         $this->o58_datacriacao_mes = ($this->o58_datacriacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_datacriacao_mes"]:$this->o58_datacriacao_mes);
         $this->o58_datacriacao_ano = ($this->o58_datacriacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_datacriacao_ano"]:$this->o58_datacriacao_ano);
         if($this->o58_datacriacao_dia != ""){
            $this->o58_datacriacao = $this->o58_datacriacao_ano."-".$this->o58_datacriacao_mes."-".$this->o58_datacriacao_dia;
         }
       }
       $this->o58_concarpeculiar = ($this->o58_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_concarpeculiar"]:$this->o58_concarpeculiar);
     }else{
       $this->o58_anousu = ($this->o58_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_anousu"]:$this->o58_anousu);
       $this->o58_coddot = ($this->o58_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["o58_coddot"]:$this->o58_coddot);
     }
   }
   // funcao para inclusao
   function incluir ($o58_anousu,$o58_coddot){ 
      $this->atualizacampos();
     if($this->o58_orgao == null ){ 
       $this->erro_sql = " Campo Código Orgão nao Informado.";
       $this->erro_campo = "o58_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o58_unidade == null ){ 
       $this->erro_sql = " Campo Código Unidade nao Informado.";
       $this->erro_campo = "o58_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o58_funcao == null ){ 
       $this->erro_sql = " Campo Código da Função nao Informado.";
       $this->erro_campo = "o58_funcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o58_subfuncao == null ){ 
       $this->erro_sql = " Campo Sub Função nao Informado.";
       $this->erro_campo = "o58_subfuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o58_programa == null ){ 
       $this->erro_sql = " Campo Programas Orçamento nao Informado.";
       $this->erro_campo = "o58_programa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o58_projativ == null ){ 
       $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
       $this->erro_campo = "o58_projativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o58_codele == null ){ 
       $this->erro_sql = " Campo Código Elemento nao Informado.";
       $this->erro_campo = "o58_codele";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o58_codigo == null ){ 
       $this->erro_sql = " Campo Tipo de Recurso nao Informado.";
       $this->erro_campo = "o58_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o58_valor == null ){ 
       $this->erro_sql = " Campo Previsão nao Informado.";
       $this->erro_campo = "o58_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o58_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "o58_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o58_localizadorgastos == null ){ 
       $this->erro_sql = " Campo Localizador dos Gastos nao Informado.";
       $this->erro_campo = "o58_localizadorgastos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o58_datacriacao == null ){ 
       $this->o58_datacriacao = "null";
     }
     if($this->o58_concarpeculiar == null ){ 
       $this->erro_sql = " Campo C.Peculiar/ C. Aplicação nao Informado.";
       $this->erro_campo = "o58_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o58_anousu = $o58_anousu; 
       $this->o58_coddot = $o58_coddot; 
     if(($this->o58_anousu == null) || ($this->o58_anousu == "") ){ 
       $this->erro_sql = " Campo o58_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o58_coddot == null) || ($this->o58_coddot == "") ){ 
       $this->erro_sql = " Campo o58_coddot nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcdotacao(
                                       o58_anousu 
                                      ,o58_coddot 
                                      ,o58_orgao 
                                      ,o58_unidade 
                                      ,o58_funcao 
                                      ,o58_subfuncao 
                                      ,o58_programa 
                                      ,o58_projativ 
                                      ,o58_codele 
                                      ,o58_codigo 
                                      ,o58_valor 
                                      ,o58_instit 
                                      ,o58_localizadorgastos 
                                      ,o58_datacriacao 
                                      ,o58_concarpeculiar 
                       )
                values (
                                $this->o58_anousu 
                               ,$this->o58_coddot 
                               ,$this->o58_orgao 
                               ,$this->o58_unidade 
                               ,$this->o58_funcao 
                               ,$this->o58_subfuncao 
                               ,$this->o58_programa 
                               ,$this->o58_projativ 
                               ,$this->o58_codele 
                               ,$this->o58_codigo 
                               ,$this->o58_valor 
                               ,$this->o58_instit 
                               ,$this->o58_localizadorgastos 
                               ,".($this->o58_datacriacao == "null" || $this->o58_datacriacao == ""?"null":"'".$this->o58_datacriacao."'")." 
                               ,'$this->o58_concarpeculiar' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dotações Lançadas ($this->o58_anousu."-".$this->o58_coddot) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dotações Lançadas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dotações Lançadas ($this->o58_anousu."-".$this->o58_coddot) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o58_anousu."-".$this->o58_coddot;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o58_anousu,$this->o58_coddot));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5285,'$this->o58_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,5286,'$this->o58_coddot','I')");
       $resac = db_query("insert into db_acount values($acount,758,5285,'','".AddSlashes(pg_result($resaco,0,'o58_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,5286,'','".AddSlashes(pg_result($resaco,0,'o58_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,5287,'','".AddSlashes(pg_result($resaco,0,'o58_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,5288,'','".AddSlashes(pg_result($resaco,0,'o58_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,5289,'','".AddSlashes(pg_result($resaco,0,'o58_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,5290,'','".AddSlashes(pg_result($resaco,0,'o58_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,5291,'','".AddSlashes(pg_result($resaco,0,'o58_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,5292,'','".AddSlashes(pg_result($resaco,0,'o58_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,5293,'','".AddSlashes(pg_result($resaco,0,'o58_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,5294,'','".AddSlashes(pg_result($resaco,0,'o58_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,5295,'','".AddSlashes(pg_result($resaco,0,'o58_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,5296,'','".AddSlashes(pg_result($resaco,0,'o58_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,14520,'','".AddSlashes(pg_result($resaco,0,'o58_localizadorgastos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,17691,'','".AddSlashes(pg_result($resaco,0,'o58_datacriacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,758,18157,'','".AddSlashes(pg_result($resaco,0,'o58_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o58_anousu=null,$o58_coddot=null) { 
      $this->atualizacampos();
     $sql = " update orcdotacao set ";
     $virgula = "";
     if(trim($this->o58_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_anousu"])){ 
       $sql  .= $virgula." o58_anousu = $this->o58_anousu ";
       $virgula = ",";
       if(trim($this->o58_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o58_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_coddot"])){ 
       $sql  .= $virgula." o58_coddot = $this->o58_coddot ";
       $virgula = ",";
       if(trim($this->o58_coddot) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "o58_coddot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_orgao"])){ 
       $sql  .= $virgula." o58_orgao = $this->o58_orgao ";
       $virgula = ",";
       if(trim($this->o58_orgao) == null ){ 
         $this->erro_sql = " Campo Código Orgão nao Informado.";
         $this->erro_campo = "o58_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_unidade"])){ 
       $sql  .= $virgula." o58_unidade = $this->o58_unidade ";
       $virgula = ",";
       if(trim($this->o58_unidade) == null ){ 
         $this->erro_sql = " Campo Código Unidade nao Informado.";
         $this->erro_campo = "o58_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_funcao"])){ 
       $sql  .= $virgula." o58_funcao = $this->o58_funcao ";
       $virgula = ",";
       if(trim($this->o58_funcao) == null ){ 
         $this->erro_sql = " Campo Código da Função nao Informado.";
         $this->erro_campo = "o58_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_subfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_subfuncao"])){ 
       $sql  .= $virgula." o58_subfuncao = $this->o58_subfuncao ";
       $virgula = ",";
       if(trim($this->o58_subfuncao) == null ){ 
         $this->erro_sql = " Campo Sub Função nao Informado.";
         $this->erro_campo = "o58_subfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_programa"])){ 
       $sql  .= $virgula." o58_programa = $this->o58_programa ";
       $virgula = ",";
       if(trim($this->o58_programa) == null ){ 
         $this->erro_sql = " Campo Programas Orçamento nao Informado.";
         $this->erro_campo = "o58_programa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_projativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_projativ"])){ 
       $sql  .= $virgula." o58_projativ = $this->o58_projativ ";
       $virgula = ",";
       if(trim($this->o58_projativ) == null ){ 
         $this->erro_sql = " Campo Projetos / Atividades nao Informado.";
         $this->erro_campo = "o58_projativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_codele)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_codele"])){ 
       $sql  .= $virgula." o58_codele = $this->o58_codele ";
       $virgula = ",";
       if(trim($this->o58_codele) == null ){ 
         $this->erro_sql = " Campo Código Elemento nao Informado.";
         $this->erro_campo = "o58_codele";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_codigo"])){ 
       $sql  .= $virgula." o58_codigo = $this->o58_codigo ";
       $virgula = ",";
       if(trim($this->o58_codigo) == null ){ 
         $this->erro_sql = " Campo Tipo de Recurso nao Informado.";
         $this->erro_campo = "o58_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_valor"])){ 
       $sql  .= $virgula." o58_valor = $this->o58_valor ";
       $virgula = ",";
       if(trim($this->o58_valor) == null ){ 
         $this->erro_sql = " Campo Previsão nao Informado.";
         $this->erro_campo = "o58_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_instit"])){ 
       $sql  .= $virgula." o58_instit = $this->o58_instit ";
       $virgula = ",";
       if(trim($this->o58_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "o58_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_localizadorgastos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_localizadorgastos"])){ 
       $sql  .= $virgula." o58_localizadorgastos = $this->o58_localizadorgastos ";
       $virgula = ",";
       if(trim($this->o58_localizadorgastos) == null ){ 
         $this->erro_sql = " Campo Localizador dos Gastos nao Informado.";
         $this->erro_campo = "o58_localizadorgastos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o58_datacriacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_datacriacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["o58_datacriacao_dia"] !="") ){ 
       $sql  .= $virgula." o58_datacriacao = '$this->o58_datacriacao' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["o58_datacriacao_dia"])){ 
         $sql  .= $virgula." o58_datacriacao = null ";
         $virgula = ",";
       }
     }
     if(trim($this->o58_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o58_concarpeculiar"])){ 
       $sql  .= $virgula." o58_concarpeculiar = '$this->o58_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->o58_concarpeculiar) == null ){ 
         $this->erro_sql = " Campo C.Peculiar/ C. Aplicação nao Informado.";
         $this->erro_campo = "o58_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o58_anousu!=null){
       $sql .= " o58_anousu = $this->o58_anousu";
     }
     if($o58_coddot!=null){
       $sql .= " and  o58_coddot = $this->o58_coddot";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o58_anousu,$this->o58_coddot));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5285,'$this->o58_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,5286,'$this->o58_coddot','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_anousu"]) || $this->o58_anousu != "")
           $resac = db_query("insert into db_acount values($acount,758,5285,'".AddSlashes(pg_result($resaco,$conresaco,'o58_anousu'))."','$this->o58_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_coddot"]) || $this->o58_coddot != "")
           $resac = db_query("insert into db_acount values($acount,758,5286,'".AddSlashes(pg_result($resaco,$conresaco,'o58_coddot'))."','$this->o58_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_orgao"]) || $this->o58_orgao != "")
           $resac = db_query("insert into db_acount values($acount,758,5287,'".AddSlashes(pg_result($resaco,$conresaco,'o58_orgao'))."','$this->o58_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_unidade"]) || $this->o58_unidade != "")
           $resac = db_query("insert into db_acount values($acount,758,5288,'".AddSlashes(pg_result($resaco,$conresaco,'o58_unidade'))."','$this->o58_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_funcao"]) || $this->o58_funcao != "")
           $resac = db_query("insert into db_acount values($acount,758,5289,'".AddSlashes(pg_result($resaco,$conresaco,'o58_funcao'))."','$this->o58_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_subfuncao"]) || $this->o58_subfuncao != "")
           $resac = db_query("insert into db_acount values($acount,758,5290,'".AddSlashes(pg_result($resaco,$conresaco,'o58_subfuncao'))."','$this->o58_subfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_programa"]) || $this->o58_programa != "")
           $resac = db_query("insert into db_acount values($acount,758,5291,'".AddSlashes(pg_result($resaco,$conresaco,'o58_programa'))."','$this->o58_programa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_projativ"]) || $this->o58_projativ != "")
           $resac = db_query("insert into db_acount values($acount,758,5292,'".AddSlashes(pg_result($resaco,$conresaco,'o58_projativ'))."','$this->o58_projativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_codele"]) || $this->o58_codele != "")
           $resac = db_query("insert into db_acount values($acount,758,5293,'".AddSlashes(pg_result($resaco,$conresaco,'o58_codele'))."','$this->o58_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_codigo"]) || $this->o58_codigo != "")
           $resac = db_query("insert into db_acount values($acount,758,5294,'".AddSlashes(pg_result($resaco,$conresaco,'o58_codigo'))."','$this->o58_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_valor"]) || $this->o58_valor != "")
           $resac = db_query("insert into db_acount values($acount,758,5295,'".AddSlashes(pg_result($resaco,$conresaco,'o58_valor'))."','$this->o58_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_instit"]) || $this->o58_instit != "")
           $resac = db_query("insert into db_acount values($acount,758,5296,'".AddSlashes(pg_result($resaco,$conresaco,'o58_instit'))."','$this->o58_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_localizadorgastos"]) || $this->o58_localizadorgastos != "")
           $resac = db_query("insert into db_acount values($acount,758,14520,'".AddSlashes(pg_result($resaco,$conresaco,'o58_localizadorgastos'))."','$this->o58_localizadorgastos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_datacriacao"]) || $this->o58_datacriacao != "")
           $resac = db_query("insert into db_acount values($acount,758,17691,'".AddSlashes(pg_result($resaco,$conresaco,'o58_datacriacao'))."','$this->o58_datacriacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o58_concarpeculiar"]) || $this->o58_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,758,18157,'".AddSlashes(pg_result($resaco,$conresaco,'o58_concarpeculiar'))."','$this->o58_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dotações Lançadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o58_anousu."-".$this->o58_coddot;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dotações Lançadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o58_anousu."-".$this->o58_coddot;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o58_anousu."-".$this->o58_coddot;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o58_anousu=null,$o58_coddot=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o58_anousu,$o58_coddot));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5285,'$o58_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,5286,'$o58_coddot','E')");
         $resac = db_query("insert into db_acount values($acount,758,5285,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,5286,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,5287,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,5288,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,5289,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,5290,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,5291,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,5292,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_projativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,5293,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,5294,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,5295,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,5296,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,14520,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_localizadorgastos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,17691,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_datacriacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,758,18157,'','".AddSlashes(pg_result($resaco,$iresaco,'o58_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcdotacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o58_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o58_anousu = $o58_anousu ";
        }
        if($o58_coddot != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o58_coddot = $o58_coddot ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dotações Lançadas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o58_anousu."-".$o58_coddot;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dotações Lançadas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o58_anousu."-".$o58_coddot;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o58_anousu."-".$o58_coddot;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcdotacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o58_anousu=null,$o58_coddot=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcdotacao ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcdotacao.o58_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and  orcelemento.o56_anousu = orcdotacao.o58_anousu";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = orcdotacao.o58_concarpeculiar";
     $sql .= "      inner join ppasubtitulolocalizadorgasto  on  ppasubtitulolocalizadorgasto.o11_sequencial = orcdotacao.o58_localizadorgastos";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join orcproduto  on  orcproduto.o22_codproduto = orcprojativ.o55_orcproduto";
     $sql .= "      inner join orcorgao  as a on   a.o40_anousu = orcunidade.o41_anousu and   a.o40_orgao = orcunidade.o41_orgao";
     $sql2 = "";
     if($dbwhere==""){
       if($o58_anousu!=null ){
         $sql2 .= " where orcdotacao.o58_anousu = $o58_anousu "; 
       } 
       if($o58_coddot!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcdotacao.o58_coddot = $o58_coddot "; 
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
   function sql_query_file ( $o58_anousu=null,$o58_coddot=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcdotacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($o58_anousu!=null ){
         $sql2 .= " where orcdotacao.o58_anousu = $o58_anousu "; 
       } 
       if($o58_coddot!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcdotacao.o58_coddot = $o58_coddot "; 
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
   function sql_query_autoriza ( $o58_anousu=null,$o58_coddot=null,$campos="*",$ordem=null,$dbwhere=""){ 
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

     $sql .= " from orcdotacao ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcdotacao.o58_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
     $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
     $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
     $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
     $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and orcelemento.o56_anousu = orcdotacao.o58_anousu ";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
     $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcunidade.o41_anousu and   d.o40_orgao = orcunidade.o41_orgao";
     $sql .= "      inner join db_departorg on db_departorg.db01_anousu = orcdotacao.o58_anousu and db_departorg.db01_orgao = orcorgao.o40_orgao and db_departorg.db01_unidade = orcunidade.o41_unidade ";    
     $sql .= "      inner join db_depart on db_depart.coddepto = db_departorg.db01_coddepto ";
     $sql .= "      inner join db_depusu on db_depusu.coddepto = db_depart.coddepto ";
     $sql .= "      inner join db_usuarios on db_usuarios.id_usuario = db_depusu.id_usuario ";
     $sql2 = "";
     if($dbwhere==""){
       if($o58_anousu!=null ){
         $sql2 .= " where orcdotacao.o58_anousu = $o58_anousu "; 
       } 
       if($o58_coddot!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcdotacao.o58_coddot = $o58_coddot "; 
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
   function sql_query_ele ( $o58_anousu=null,$o58_coddot=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcdotacao ";
     $sql .= "      inner join db_config  on  db_config.codigo = orcdotacao.o58_instit and db_config.codigo = ".db_getsession("DB_instit");
     $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and orcelemento.o56_anousu = orcdotacao.o58_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($o58_anousu!=null ){
         $sql2 .= " where orcdotacao.o58_anousu = $o58_anousu "; 
       } 
       if($o58_coddot!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcdotacao.o58_coddot = $o58_coddot "; 
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
  
  function sql_query_dotacao( $o58_anousu=null,$o58_coddot=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from orcdotacao ";
    $sql .= "      inner join db_config    on db_config.codigo = orcdotacao.o58_instit           "; 
    $sql .= " 													  and db_config.codigo = ".db_getsession("DB_instit");
    $sql .= "      inner join orcelemento  on orcelemento.o56_codele    = orcdotacao.o58_codele   "; 
    $sql .= "                             and orcelemento.o56_anousu    = orcdotacao.o58_anousu      ";
    $sql .= "      inner join orcprojativ  on orcprojativ.o55_projativ  = orcdotacao.o58_projativ "; 
    $sql .= "                             and orcprojativ.o55_anousu    = orcdotacao.o58_anousu ";
    $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo     = orcdotacao.o58_codigo ";
    
    $sql2 = "";
    if($dbwhere=="") {
      
      if($o58_anousu!=null ) {
        $sql2 .= " where orcdotacao.o58_anousu = $o58_anousu ";
      }
      if($o58_coddot!=null ) {
        
        if($sql2!="") {
          $sql2 .= " and ";
        }else{
          $sql2 .= " where ";
        }
        $sql2 .= " orcdotacao.o58_coddot = $o58_coddot ";
       } 
     } else if($dbwhere != "") {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
    if($ordem != null ) {
      
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>