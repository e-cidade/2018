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

//MODULO: escola
//CLASSE DA ENTIDADE edu_parametros
class cl_edu_parametros { 
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
   var $ed233_i_codigo = 0; 
   var $ed233_i_escola = 0; 
   var $ed233_c_decimais = null; 
   var $ed233_c_notabranca = null; 
   var $ed233_f_medidaaluno = 0; 
   var $ed233_c_limitemov = null; 
   var $ed233_c_database = null; 
   var $ed233_c_consistirmat = null; 
   var $ed233_c_avalalternativa = null; 
   var $ed233_i_idadevotacao = 0; 
   var $ed233_i_habilitaordemalfabeticaturma = 0; 
   var $ed233_deslocamentocursor = 0; 
   var $ed233_formalancamentoparecer = 0; 
   var $ed233_bloqueioalteracaoavaliacao = 'f'; 
   var $ed233_reclassificaetapaanterior = 'f'; 
   var $ed233_apresentarnotaproporcional = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed233_i_codigo = int8 = Código 
                 ed233_i_escola = int8 = Escola 
                 ed233_c_decimais = char(1) = Notas com casas decimais 
                 ed233_c_notabranca = char(1) = Calcular média parcial 
                 ed233_f_medidaaluno = float8 = Medida em m2 por aluno em sala de aula 
                 ed233_c_limitemov = char(5) = Dia / Mês Limite Movimentação 
                 ed233_c_database = char(5) = Data base para calculo de idade 
                 ed233_c_consistirmat = char(1) = Consistir Mat. Registro no Histórico 
                 ed233_c_avalalternativa = char(1) = Habilitar Avaliações Alternativas 
                 ed233_i_idadevotacao = int4 = Idade Mínima para Votação 
                 ed233_i_habilitaordemalfabeticaturma = int4 = Habilita Botão Ordenar Alfabeticamente 
                 ed233_deslocamentocursor = int4 = Deslocamento do Cursor 
                 ed233_formalancamentoparecer = int4 = Forma de Lançamento do Parecer 
                 ed233_bloqueioalteracaoavaliacao = bool = Bloquear Alteração das Avaliações 
                 ed233_reclassificaetapaanterior = bool = Reclassifica para Etapa Anterior 
                 ed233_apresentarnotaproporcional = bool = Apresentar nota proporcional 
                 ";
   //funcao construtor da classe 
   function cl_edu_parametros() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("edu_parametros"); 
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
       $this->ed233_i_codigo = ($this->ed233_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_i_codigo"]:$this->ed233_i_codigo);
       $this->ed233_i_escola = ($this->ed233_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_i_escola"]:$this->ed233_i_escola);
       $this->ed233_c_decimais = ($this->ed233_c_decimais == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_c_decimais"]:$this->ed233_c_decimais);
       $this->ed233_c_notabranca = ($this->ed233_c_notabranca == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_c_notabranca"]:$this->ed233_c_notabranca);
       $this->ed233_f_medidaaluno = ($this->ed233_f_medidaaluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_f_medidaaluno"]:$this->ed233_f_medidaaluno);
       $this->ed233_c_limitemov = ($this->ed233_c_limitemov == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_c_limitemov"]:$this->ed233_c_limitemov);
       $this->ed233_c_database = ($this->ed233_c_database == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_c_database"]:$this->ed233_c_database);
       $this->ed233_c_consistirmat = ($this->ed233_c_consistirmat == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_c_consistirmat"]:$this->ed233_c_consistirmat);
       $this->ed233_c_avalalternativa = ($this->ed233_c_avalalternativa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_c_avalalternativa"]:$this->ed233_c_avalalternativa);
       $this->ed233_i_idadevotacao = ($this->ed233_i_idadevotacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_i_idadevotacao"]:$this->ed233_i_idadevotacao);
       $this->ed233_i_habilitaordemalfabeticaturma = ($this->ed233_i_habilitaordemalfabeticaturma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_i_habilitaordemalfabeticaturma"]:$this->ed233_i_habilitaordemalfabeticaturma);
       $this->ed233_deslocamentocursor = ($this->ed233_deslocamentocursor == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_deslocamentocursor"]:$this->ed233_deslocamentocursor);
       $this->ed233_formalancamentoparecer = ($this->ed233_formalancamentoparecer == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_formalancamentoparecer"]:$this->ed233_formalancamentoparecer);
       $this->ed233_bloqueioalteracaoavaliacao = ($this->ed233_bloqueioalteracaoavaliacao == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed233_bloqueioalteracaoavaliacao"]:$this->ed233_bloqueioalteracaoavaliacao);
       $this->ed233_reclassificaetapaanterior = ($this->ed233_reclassificaetapaanterior == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed233_reclassificaetapaanterior"]:$this->ed233_reclassificaetapaanterior);
       $this->ed233_apresentarnotaproporcional = ($this->ed233_apresentarnotaproporcional == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed233_apresentarnotaproporcional"]:$this->ed233_apresentarnotaproporcional);
     }else{
       $this->ed233_i_codigo = ($this->ed233_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed233_i_codigo"]:$this->ed233_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($ed233_i_codigo){ 
      $this->atualizacampos();
     if($this->ed233_i_escola == null ){ 
       $this->erro_sql = " Campo Escola não informado.";
       $this->erro_campo = "ed233_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_c_decimais == null ){ 
       $this->erro_sql = " Campo Notas com casas decimais não informado.";
       $this->erro_campo = "ed233_c_decimais";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_c_notabranca == null ){ 
       $this->erro_sql = " Campo Calcular média parcial não informado.";
       $this->erro_campo = "ed233_c_notabranca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_f_medidaaluno == null ){ 
       $this->erro_sql = " Campo Medida em m2 por aluno em sala de aula não informado.";
       $this->erro_campo = "ed233_f_medidaaluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_c_limitemov == null ){ 
       $this->erro_sql = " Campo Dia / Mês Limite Movimentação não informado.";
       $this->erro_campo = "ed233_c_limitemov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_c_database == null ){ 
       $this->erro_sql = " Campo Data base para calculo de idade não informado.";
       $this->erro_campo = "ed233_c_database";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_c_consistirmat == null ){ 
       $this->erro_sql = " Campo Consistir Mat. Registro no Histórico não informado.";
       $this->erro_campo = "ed233_c_consistirmat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_c_avalalternativa == null ){ 
       $this->erro_sql = " Campo Habilitar Avaliações Alternativas não informado.";
       $this->erro_campo = "ed233_c_avalalternativa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_i_idadevotacao == null ){ 
       $this->erro_sql = " Campo Idade Mínima para Votação não informado.";
       $this->erro_campo = "ed233_i_idadevotacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_i_habilitaordemalfabeticaturma == null ){ 
       $this->erro_sql = " Campo Habilita Botão Ordenar Alfabeticamente não informado.";
       $this->erro_campo = "ed233_i_habilitaordemalfabeticaturma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_deslocamentocursor == null ){ 
       $this->erro_sql = " Campo Deslocamento do Cursor não informado.";
       $this->erro_campo = "ed233_deslocamentocursor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_formalancamentoparecer == null ){ 
       $this->erro_sql = " Campo Forma de Lançamento do Parecer não informado.";
       $this->erro_campo = "ed233_formalancamentoparecer";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_bloqueioalteracaoavaliacao == null ){ 
       $this->erro_sql = " Campo Bloquear Alteração das Avaliações não informado.";
       $this->erro_campo = "ed233_bloqueioalteracaoavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed233_reclassificaetapaanterior == null ){ 
       $this->ed233_reclassificaetapaanterior = "f";
     }
     if($this->ed233_apresentarnotaproporcional == null ){ 
       $this->erro_sql = " Campo Apresentar nota proporcional não informado.";
       $this->erro_campo = "ed233_apresentarnotaproporcional";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed233_i_codigo == "" || $ed233_i_codigo == null ){
       $result = db_query("select nextval('edu_parametros_ed233_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: edu_parametros_ed233_i_codigo_seq do campo: ed233_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed233_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from edu_parametros_ed233_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed233_i_codigo)){
         $this->erro_sql = " Campo ed233_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed233_i_codigo = $ed233_i_codigo; 
       }
     }
     if(($this->ed233_i_codigo == null) || ($this->ed233_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed233_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into edu_parametros(
                                       ed233_i_codigo 
                                      ,ed233_i_escola 
                                      ,ed233_c_decimais 
                                      ,ed233_c_notabranca 
                                      ,ed233_f_medidaaluno 
                                      ,ed233_c_limitemov 
                                      ,ed233_c_database 
                                      ,ed233_c_consistirmat 
                                      ,ed233_c_avalalternativa 
                                      ,ed233_i_idadevotacao 
                                      ,ed233_i_habilitaordemalfabeticaturma 
                                      ,ed233_deslocamentocursor 
                                      ,ed233_formalancamentoparecer 
                                      ,ed233_bloqueioalteracaoavaliacao 
                                      ,ed233_reclassificaetapaanterior 
                                      ,ed233_apresentarnotaproporcional 
                       )
                values (
                                $this->ed233_i_codigo 
                               ,$this->ed233_i_escola 
                               ,'$this->ed233_c_decimais' 
                               ,'$this->ed233_c_notabranca' 
                               ,$this->ed233_f_medidaaluno 
                               ,'$this->ed233_c_limitemov' 
                               ,'$this->ed233_c_database' 
                               ,'$this->ed233_c_consistirmat' 
                               ,'$this->ed233_c_avalalternativa' 
                               ,$this->ed233_i_idadevotacao 
                               ,$this->ed233_i_habilitaordemalfabeticaturma 
                               ,$this->ed233_deslocamentocursor 
                               ,$this->ed233_formalancamentoparecer 
                               ,'$this->ed233_bloqueioalteracaoavaliacao' 
                               ,'$this->ed233_reclassificaetapaanterior' 
                               ,'$this->ed233_apresentarnotaproporcional' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parâmetros da Educação ($this->ed233_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parâmetros da Educação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parâmetros da Educação ($this->ed233_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed233_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed233_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11720,'$this->ed233_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2019,11720,'','".AddSlashes(pg_result($resaco,0,'ed233_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,11721,'','".AddSlashes(pg_result($resaco,0,'ed233_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,11722,'','".AddSlashes(pg_result($resaco,0,'ed233_c_decimais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,11853,'','".AddSlashes(pg_result($resaco,0,'ed233_c_notabranca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,13456,'','".AddSlashes(pg_result($resaco,0,'ed233_f_medidaaluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,13632,'','".AddSlashes(pg_result($resaco,0,'ed233_c_limitemov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,13761,'','".AddSlashes(pg_result($resaco,0,'ed233_c_database'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,15465,'','".AddSlashes(pg_result($resaco,0,'ed233_c_consistirmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,17114,'','".AddSlashes(pg_result($resaco,0,'ed233_c_avalalternativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,17456,'','".AddSlashes(pg_result($resaco,0,'ed233_i_idadevotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,17603,'','".AddSlashes(pg_result($resaco,0,'ed233_i_habilitaordemalfabeticaturma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,18555,'','".AddSlashes(pg_result($resaco,0,'ed233_deslocamentocursor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,19274,'','".AddSlashes(pg_result($resaco,0,'ed233_formalancamentoparecer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,19995,'','".AddSlashes(pg_result($resaco,0,'ed233_bloqueioalteracaoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,20359,'','".AddSlashes(pg_result($resaco,0,'ed233_reclassificaetapaanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2019,21940,'','".AddSlashes(pg_result($resaco,0,'ed233_apresentarnotaproporcional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed233_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update edu_parametros set ";
     $virgula = "";
     if(trim($this->ed233_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_i_codigo"])){ 
       $sql  .= $virgula." ed233_i_codigo = $this->ed233_i_codigo ";
       $virgula = ",";
       if(trim($this->ed233_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed233_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_i_escola"])){ 
       $sql  .= $virgula." ed233_i_escola = $this->ed233_i_escola ";
       $virgula = ",";
       if(trim($this->ed233_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola não informado.";
         $this->erro_campo = "ed233_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_c_decimais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_c_decimais"])){ 
       $sql  .= $virgula." ed233_c_decimais = '$this->ed233_c_decimais' ";
       $virgula = ",";
       if(trim($this->ed233_c_decimais) == null ){ 
         $this->erro_sql = " Campo Notas com casas decimais não informado.";
         $this->erro_campo = "ed233_c_decimais";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_c_notabranca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_c_notabranca"])){ 
       $sql  .= $virgula." ed233_c_notabranca = '$this->ed233_c_notabranca' ";
       $virgula = ",";
       if(trim($this->ed233_c_notabranca) == null ){ 
         $this->erro_sql = " Campo Calcular média parcial não informado.";
         $this->erro_campo = "ed233_c_notabranca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_f_medidaaluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_f_medidaaluno"])){ 
       $sql  .= $virgula." ed233_f_medidaaluno = $this->ed233_f_medidaaluno ";
       $virgula = ",";
       if(trim($this->ed233_f_medidaaluno) == null ){ 
         $this->erro_sql = " Campo Medida em m2 por aluno em sala de aula não informado.";
         $this->erro_campo = "ed233_f_medidaaluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_c_limitemov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_c_limitemov"])){ 
       $sql  .= $virgula." ed233_c_limitemov = '$this->ed233_c_limitemov' ";
       $virgula = ",";
       if(trim($this->ed233_c_limitemov) == null ){ 
         $this->erro_sql = " Campo Dia / Mês Limite Movimentação não informado.";
         $this->erro_campo = "ed233_c_limitemov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_c_database)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_c_database"])){ 
       $sql  .= $virgula." ed233_c_database = '$this->ed233_c_database' ";
       $virgula = ",";
       if(trim($this->ed233_c_database) == null ){ 
         $this->erro_sql = " Campo Data base para calculo de idade não informado.";
         $this->erro_campo = "ed233_c_database";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_c_consistirmat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_c_consistirmat"])){ 
       $sql  .= $virgula." ed233_c_consistirmat = '$this->ed233_c_consistirmat' ";
       $virgula = ",";
       if(trim($this->ed233_c_consistirmat) == null ){ 
         $this->erro_sql = " Campo Consistir Mat. Registro no Histórico não informado.";
         $this->erro_campo = "ed233_c_consistirmat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_c_avalalternativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_c_avalalternativa"])){ 
       $sql  .= $virgula." ed233_c_avalalternativa = '$this->ed233_c_avalalternativa' ";
       $virgula = ",";
       if(trim($this->ed233_c_avalalternativa) == null ){ 
         $this->erro_sql = " Campo Habilitar Avaliações Alternativas não informado.";
         $this->erro_campo = "ed233_c_avalalternativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_i_idadevotacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_i_idadevotacao"])){ 
       $sql  .= $virgula." ed233_i_idadevotacao = $this->ed233_i_idadevotacao ";
       $virgula = ",";
       if(trim($this->ed233_i_idadevotacao) == null ){ 
         $this->erro_sql = " Campo Idade Mínima para Votação não informado.";
         $this->erro_campo = "ed233_i_idadevotacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_i_habilitaordemalfabeticaturma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_i_habilitaordemalfabeticaturma"])){ 
       $sql  .= $virgula." ed233_i_habilitaordemalfabeticaturma = $this->ed233_i_habilitaordemalfabeticaturma ";
       $virgula = ",";
       if(trim($this->ed233_i_habilitaordemalfabeticaturma) == null ){ 
         $this->erro_sql = " Campo Habilita Botão Ordenar Alfabeticamente não informado.";
         $this->erro_campo = "ed233_i_habilitaordemalfabeticaturma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_deslocamentocursor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_deslocamentocursor"])){ 
       $sql  .= $virgula." ed233_deslocamentocursor = $this->ed233_deslocamentocursor ";
       $virgula = ",";
       if(trim($this->ed233_deslocamentocursor) == null ){ 
         $this->erro_sql = " Campo Deslocamento do Cursor não informado.";
         $this->erro_campo = "ed233_deslocamentocursor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_formalancamentoparecer)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_formalancamentoparecer"])){ 
       $sql  .= $virgula." ed233_formalancamentoparecer = $this->ed233_formalancamentoparecer ";
       $virgula = ",";
       if(trim($this->ed233_formalancamentoparecer) == null ){ 
         $this->erro_sql = " Campo Forma de Lançamento do Parecer não informado.";
         $this->erro_campo = "ed233_formalancamentoparecer";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_bloqueioalteracaoavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_bloqueioalteracaoavaliacao"])){ 
       $sql  .= $virgula." ed233_bloqueioalteracaoavaliacao = '$this->ed233_bloqueioalteracaoavaliacao' ";
       $virgula = ",";
       if(trim($this->ed233_bloqueioalteracaoavaliacao) == null ){ 
         $this->erro_sql = " Campo Bloquear Alteração das Avaliações não informado.";
         $this->erro_campo = "ed233_bloqueioalteracaoavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed233_reclassificaetapaanterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_reclassificaetapaanterior"])){ 
       $sql  .= $virgula." ed233_reclassificaetapaanterior = '$this->ed233_reclassificaetapaanterior' ";
       $virgula = ",";
     }
     if(trim($this->ed233_apresentarnotaproporcional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed233_apresentarnotaproporcional"])){ 
       $sql  .= $virgula." ed233_apresentarnotaproporcional = '$this->ed233_apresentarnotaproporcional' ";
       $virgula = ",";
       if(trim($this->ed233_apresentarnotaproporcional) == null ){ 
         $this->erro_sql = " Campo Apresentar nota proporcional não informado.";
         $this->erro_campo = "ed233_apresentarnotaproporcional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed233_i_codigo!=null){
       $sql .= " ed233_i_codigo = $this->ed233_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed233_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,11720,'$this->ed233_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_i_codigo"]) || $this->ed233_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2019,11720,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_i_codigo'))."','$this->ed233_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_i_escola"]) || $this->ed233_i_escola != "")
             $resac = db_query("insert into db_acount values($acount,2019,11721,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_i_escola'))."','$this->ed233_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_c_decimais"]) || $this->ed233_c_decimais != "")
             $resac = db_query("insert into db_acount values($acount,2019,11722,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_c_decimais'))."','$this->ed233_c_decimais',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_c_notabranca"]) || $this->ed233_c_notabranca != "")
             $resac = db_query("insert into db_acount values($acount,2019,11853,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_c_notabranca'))."','$this->ed233_c_notabranca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_f_medidaaluno"]) || $this->ed233_f_medidaaluno != "")
             $resac = db_query("insert into db_acount values($acount,2019,13456,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_f_medidaaluno'))."','$this->ed233_f_medidaaluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_c_limitemov"]) || $this->ed233_c_limitemov != "")
             $resac = db_query("insert into db_acount values($acount,2019,13632,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_c_limitemov'))."','$this->ed233_c_limitemov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_c_database"]) || $this->ed233_c_database != "")
             $resac = db_query("insert into db_acount values($acount,2019,13761,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_c_database'))."','$this->ed233_c_database',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_c_consistirmat"]) || $this->ed233_c_consistirmat != "")
             $resac = db_query("insert into db_acount values($acount,2019,15465,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_c_consistirmat'))."','$this->ed233_c_consistirmat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_c_avalalternativa"]) || $this->ed233_c_avalalternativa != "")
             $resac = db_query("insert into db_acount values($acount,2019,17114,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_c_avalalternativa'))."','$this->ed233_c_avalalternativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_i_idadevotacao"]) || $this->ed233_i_idadevotacao != "")
             $resac = db_query("insert into db_acount values($acount,2019,17456,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_i_idadevotacao'))."','$this->ed233_i_idadevotacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_i_habilitaordemalfabeticaturma"]) || $this->ed233_i_habilitaordemalfabeticaturma != "")
             $resac = db_query("insert into db_acount values($acount,2019,17603,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_i_habilitaordemalfabeticaturma'))."','$this->ed233_i_habilitaordemalfabeticaturma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_deslocamentocursor"]) || $this->ed233_deslocamentocursor != "")
             $resac = db_query("insert into db_acount values($acount,2019,18555,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_deslocamentocursor'))."','$this->ed233_deslocamentocursor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_formalancamentoparecer"]) || $this->ed233_formalancamentoparecer != "")
             $resac = db_query("insert into db_acount values($acount,2019,19274,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_formalancamentoparecer'))."','$this->ed233_formalancamentoparecer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_bloqueioalteracaoavaliacao"]) || $this->ed233_bloqueioalteracaoavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,2019,19995,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_bloqueioalteracaoavaliacao'))."','$this->ed233_bloqueioalteracaoavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_reclassificaetapaanterior"]) || $this->ed233_reclassificaetapaanterior != "")
             $resac = db_query("insert into db_acount values($acount,2019,20359,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_reclassificaetapaanterior'))."','$this->ed233_reclassificaetapaanterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed233_apresentarnotaproporcional"]) || $this->ed233_apresentarnotaproporcional != "")
             $resac = db_query("insert into db_acount values($acount,2019,21940,'".AddSlashes(pg_result($resaco,$conresaco,'ed233_apresentarnotaproporcional'))."','$this->ed233_apresentarnotaproporcional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros da Educação não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed233_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros da Educação não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed233_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed233_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed233_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed233_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,11720,'$ed233_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2019,11720,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,11721,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,11722,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_c_decimais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,11853,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_c_notabranca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,13456,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_f_medidaaluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,13632,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_c_limitemov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,13761,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_c_database'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,15465,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_c_consistirmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,17114,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_c_avalalternativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,17456,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_i_idadevotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,17603,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_i_habilitaordemalfabeticaturma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,18555,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_deslocamentocursor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,19274,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_formalancamentoparecer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,19995,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_bloqueioalteracaoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,20359,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_reclassificaetapaanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2019,21940,'','".AddSlashes(pg_result($resaco,$iresaco,'ed233_apresentarnotaproporcional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from edu_parametros
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed233_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed233_i_codigo = $ed233_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parâmetros da Educação não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed233_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parâmetros da Educação não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed233_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed233_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:edu_parametros";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed233_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from edu_parametros ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = edu_parametros.ed233_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      left  join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join censoorgreg  on  censoorgreg.ed263_i_codigo = escola.ed18_i_censoorgreg";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed233_i_codigo)) {
         $sql2 .= " where edu_parametros.ed233_i_codigo = $ed233_i_codigo "; 
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
   public function sql_query_file ($ed233_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from edu_parametros ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed233_i_codigo)){
         $sql2 .= " where edu_parametros.ed233_i_codigo = $ed233_i_codigo "; 
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

}
