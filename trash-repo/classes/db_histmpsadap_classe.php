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

//MODULO: educação
//CLASSE DA ENTIDADE histmpsadap
class cl_histmpsadap { 
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
   var $ed66_i_codigo = 0; 
   var $ed66_i_historicomps = 0; 
   var $ed66_i_disciplina = 0; 
   var $ed66_i_anoadap = 0; 
   var $ed66_i_periodoadap = 0; 
   var $ed66_c_tiporesultado = null; 
   var $ed66_i_qtdch = 0; 
   var $ed66_t_resultobtido = null; 
   var $ed66_c_resultadofinal = null; 
   var $ed66_c_tipoadap = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed66_i_codigo = int8 = Código 
                 ed66_i_historicomps = int8 = Historico MPS 
                 ed66_i_disciplina = int8 = Disciplina 
                 ed66_i_anoadap = int4 = Ano de Adaptação 
                 ed66_i_periodoadap = int4 = Período de Adaptação 
                 ed66_c_tiporesultado = char(1) = Tipo de Resultado 
                 ed66_i_qtdch = int4 = Carga Horária 
                 ed66_t_resultobtido = text = Resultado Obtido 
                 ed66_c_resultadofinal = char(1) = Resultado Final 
                 ed66_c_tipoadap = char(1) = Tipo de Adaptação 
                 ";
   //funcao construtor da classe 
   function cl_histmpsadap() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("histmpsadap"); 
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
       $this->ed66_i_codigo = ($this->ed66_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed66_i_codigo"]:$this->ed66_i_codigo);
       $this->ed66_i_historicomps = ($this->ed66_i_historicomps == ""?@$GLOBALS["HTTP_POST_VARS"]["ed66_i_historicomps"]:$this->ed66_i_historicomps);
       $this->ed66_i_disciplina = ($this->ed66_i_disciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed66_i_disciplina"]:$this->ed66_i_disciplina);
       $this->ed66_i_anoadap = ($this->ed66_i_anoadap == ""?@$GLOBALS["HTTP_POST_VARS"]["ed66_i_anoadap"]:$this->ed66_i_anoadap);
       $this->ed66_i_periodoadap = ($this->ed66_i_periodoadap == ""?@$GLOBALS["HTTP_POST_VARS"]["ed66_i_periodoadap"]:$this->ed66_i_periodoadap);
       $this->ed66_c_tiporesultado = ($this->ed66_c_tiporesultado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed66_c_tiporesultado"]:$this->ed66_c_tiporesultado);
       $this->ed66_i_qtdch = ($this->ed66_i_qtdch == ""?@$GLOBALS["HTTP_POST_VARS"]["ed66_i_qtdch"]:$this->ed66_i_qtdch);
       $this->ed66_t_resultobtido = ($this->ed66_t_resultobtido == ""?@$GLOBALS["HTTP_POST_VARS"]["ed66_t_resultobtido"]:$this->ed66_t_resultobtido);
       $this->ed66_c_resultadofinal = ($this->ed66_c_resultadofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed66_c_resultadofinal"]:$this->ed66_c_resultadofinal);
       $this->ed66_c_tipoadap = ($this->ed66_c_tipoadap == ""?@$GLOBALS["HTTP_POST_VARS"]["ed66_c_tipoadap"]:$this->ed66_c_tipoadap);
     }else{
       $this->ed66_i_codigo = ($this->ed66_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed66_i_codigo"]:$this->ed66_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed66_i_codigo){ 
      $this->atualizacampos();
     if($this->ed66_i_historicomps == null ){ 
       $this->erro_sql = " Campo Historico MPS nao Informado.";
       $this->erro_campo = "ed66_i_historicomps";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed66_i_disciplina == null ){ 
       $this->erro_sql = " Campo Disciplina nao Informado.";
       $this->erro_campo = "ed66_i_disciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed66_i_anoadap == null ){ 
       $this->erro_sql = " Campo Ano de Adaptação nao Informado.";
       $this->erro_campo = "ed66_i_anoadap";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed66_i_periodoadap == null ){ 
       $this->erro_sql = " Campo Período de Adaptação nao Informado.";
       $this->erro_campo = "ed66_i_periodoadap";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed66_c_tiporesultado == null ){ 
       $this->erro_sql = " Campo Tipo de Resultado nao Informado.";
       $this->erro_campo = "ed66_c_tiporesultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed66_i_qtdch == null ){ 
       $this->ed66_i_qtdch = "null";
     }
     if($this->ed66_t_resultobtido == null ){ 
       $this->erro_sql = " Campo Resultado Obtido nao Informado.";
       $this->erro_campo = "ed66_t_resultobtido";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed66_c_resultadofinal == null ){ 
       $this->erro_sql = " Campo Resultado Final nao Informado.";
       $this->erro_campo = "ed66_c_resultadofinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed66_c_tipoadap == null ){ 
       $this->erro_sql = " Campo Tipo de Adaptação nao Informado.";
       $this->erro_campo = "ed66_c_tipoadap";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed66_i_codigo == "" || $ed66_i_codigo == null ){
       $result = db_query("select nextval('histmpsadap_ed66_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: histmpsadap_ed66_i_codigo_seq do campo: ed66_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed66_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from histmpsadap_ed66_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed66_i_codigo)){
         $this->erro_sql = " Campo ed66_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed66_i_codigo = $ed66_i_codigo; 
       }
     }
     if(($this->ed66_i_codigo == null) || ($this->ed66_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed66_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into histmpsadap(
                                       ed66_i_codigo 
                                      ,ed66_i_historicomps 
                                      ,ed66_i_disciplina 
                                      ,ed66_i_anoadap 
                                      ,ed66_i_periodoadap 
                                      ,ed66_c_tiporesultado 
                                      ,ed66_i_qtdch 
                                      ,ed66_t_resultobtido 
                                      ,ed66_c_resultadofinal 
                                      ,ed66_c_tipoadap 
                       )
                values (
                                $this->ed66_i_codigo 
                               ,$this->ed66_i_historicomps 
                               ,$this->ed66_i_disciplina 
                               ,$this->ed66_i_anoadap 
                               ,$this->ed66_i_periodoadap 
                               ,'$this->ed66_c_tiporesultado' 
                               ,$this->ed66_i_qtdch 
                               ,'$this->ed66_t_resultobtido' 
                               ,'$this->ed66_c_resultadofinal' 
                               ,'$this->ed66_c_tipoadap' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Histórico MPS Disciplina com Adaptação ($this->ed66_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Histórico MPS Disciplina com Adaptação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Histórico MPS Disciplina com Adaptação ($this->ed66_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed66_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed66_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008791,'$this->ed66_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010136,1008791,'','".AddSlashes(pg_result($resaco,0,'ed66_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010136,1008792,'','".AddSlashes(pg_result($resaco,0,'ed66_i_historicomps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010136,1008793,'','".AddSlashes(pg_result($resaco,0,'ed66_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010136,1008794,'','".AddSlashes(pg_result($resaco,0,'ed66_i_anoadap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010136,1008795,'','".AddSlashes(pg_result($resaco,0,'ed66_i_periodoadap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010136,1008796,'','".AddSlashes(pg_result($resaco,0,'ed66_c_tiporesultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010136,1008797,'','".AddSlashes(pg_result($resaco,0,'ed66_i_qtdch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010136,1008798,'','".AddSlashes(pg_result($resaco,0,'ed66_t_resultobtido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010136,1008799,'','".AddSlashes(pg_result($resaco,0,'ed66_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010136,1008800,'','".AddSlashes(pg_result($resaco,0,'ed66_c_tipoadap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed66_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update histmpsadap set ";
     $virgula = "";
     if(trim($this->ed66_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_codigo"])){ 
       $sql  .= $virgula." ed66_i_codigo = $this->ed66_i_codigo ";
       $virgula = ",";
       if(trim($this->ed66_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed66_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed66_i_historicomps)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_historicomps"])){ 
       $sql  .= $virgula." ed66_i_historicomps = $this->ed66_i_historicomps ";
       $virgula = ",";
       if(trim($this->ed66_i_historicomps) == null ){ 
         $this->erro_sql = " Campo Historico MPS nao Informado.";
         $this->erro_campo = "ed66_i_historicomps";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed66_i_disciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_disciplina"])){ 
       $sql  .= $virgula." ed66_i_disciplina = $this->ed66_i_disciplina ";
       $virgula = ",";
       if(trim($this->ed66_i_disciplina) == null ){ 
         $this->erro_sql = " Campo Disciplina nao Informado.";
         $this->erro_campo = "ed66_i_disciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed66_i_anoadap)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_anoadap"])){ 
       $sql  .= $virgula." ed66_i_anoadap = $this->ed66_i_anoadap ";
       $virgula = ",";
       if(trim($this->ed66_i_anoadap) == null ){ 
         $this->erro_sql = " Campo Ano de Adaptação nao Informado.";
         $this->erro_campo = "ed66_i_anoadap";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed66_i_periodoadap)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_periodoadap"])){ 
       $sql  .= $virgula." ed66_i_periodoadap = $this->ed66_i_periodoadap ";
       $virgula = ",";
       if(trim($this->ed66_i_periodoadap) == null ){ 
         $this->erro_sql = " Campo Período de Adaptação nao Informado.";
         $this->erro_campo = "ed66_i_periodoadap";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed66_c_tiporesultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed66_c_tiporesultado"])){ 
       $sql  .= $virgula." ed66_c_tiporesultado = '$this->ed66_c_tiporesultado' ";
       $virgula = ",";
       if(trim($this->ed66_c_tiporesultado) == null ){ 
         $this->erro_sql = " Campo Tipo de Resultado nao Informado.";
         $this->erro_campo = "ed66_c_tiporesultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed66_i_qtdch)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_qtdch"])){ 
        if(trim($this->ed66_i_qtdch)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_qtdch"])){ 
           $this->ed66_i_qtdch = "0" ; 
        } 
       $sql  .= $virgula." ed66_i_qtdch = $this->ed66_i_qtdch ";
       $virgula = ",";
     }
     if(trim($this->ed66_t_resultobtido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed66_t_resultobtido"])){ 
       $sql  .= $virgula." ed66_t_resultobtido = '$this->ed66_t_resultobtido' ";
       $virgula = ",";
       if(trim($this->ed66_t_resultobtido) == null ){ 
         $this->erro_sql = " Campo Resultado Obtido nao Informado.";
         $this->erro_campo = "ed66_t_resultobtido";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed66_c_resultadofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed66_c_resultadofinal"])){ 
       $sql  .= $virgula." ed66_c_resultadofinal = '$this->ed66_c_resultadofinal' ";
       $virgula = ",";
       if(trim($this->ed66_c_resultadofinal) == null ){ 
         $this->erro_sql = " Campo Resultado Final nao Informado.";
         $this->erro_campo = "ed66_c_resultadofinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed66_c_tipoadap)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed66_c_tipoadap"])){ 
       $sql  .= $virgula." ed66_c_tipoadap = '$this->ed66_c_tipoadap' ";
       $virgula = ",";
       if(trim($this->ed66_c_tipoadap) == null ){ 
         $this->erro_sql = " Campo Tipo de Adaptação nao Informado.";
         $this->erro_campo = "ed66_c_tipoadap";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed66_i_codigo!=null){
       $sql .= " ed66_i_codigo = $this->ed66_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed66_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008791,'$this->ed66_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010136,1008791,'".AddSlashes(pg_result($resaco,$conresaco,'ed66_i_codigo'))."','$this->ed66_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_historicomps"]))
           $resac = db_query("insert into db_acount values($acount,1010136,1008792,'".AddSlashes(pg_result($resaco,$conresaco,'ed66_i_historicomps'))."','$this->ed66_i_historicomps',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_disciplina"]))
           $resac = db_query("insert into db_acount values($acount,1010136,1008793,'".AddSlashes(pg_result($resaco,$conresaco,'ed66_i_disciplina'))."','$this->ed66_i_disciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_anoadap"]))
           $resac = db_query("insert into db_acount values($acount,1010136,1008794,'".AddSlashes(pg_result($resaco,$conresaco,'ed66_i_anoadap'))."','$this->ed66_i_anoadap',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_periodoadap"]))
           $resac = db_query("insert into db_acount values($acount,1010136,1008795,'".AddSlashes(pg_result($resaco,$conresaco,'ed66_i_periodoadap'))."','$this->ed66_i_periodoadap',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed66_c_tiporesultado"]))
           $resac = db_query("insert into db_acount values($acount,1010136,1008796,'".AddSlashes(pg_result($resaco,$conresaco,'ed66_c_tiporesultado'))."','$this->ed66_c_tiporesultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed66_i_qtdch"]))
           $resac = db_query("insert into db_acount values($acount,1010136,1008797,'".AddSlashes(pg_result($resaco,$conresaco,'ed66_i_qtdch'))."','$this->ed66_i_qtdch',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed66_t_resultobtido"]))
           $resac = db_query("insert into db_acount values($acount,1010136,1008798,'".AddSlashes(pg_result($resaco,$conresaco,'ed66_t_resultobtido'))."','$this->ed66_t_resultobtido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed66_c_resultadofinal"]))
           $resac = db_query("insert into db_acount values($acount,1010136,1008799,'".AddSlashes(pg_result($resaco,$conresaco,'ed66_c_resultadofinal'))."','$this->ed66_c_resultadofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed66_c_tipoadap"]))
           $resac = db_query("insert into db_acount values($acount,1010136,1008800,'".AddSlashes(pg_result($resaco,$conresaco,'ed66_c_tipoadap'))."','$this->ed66_c_tipoadap',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico MPS Disciplina com Adaptação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed66_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Histórico MPS Disciplina com Adaptação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed66_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed66_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed66_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed66_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008791,'$ed66_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010136,1008791,'','".AddSlashes(pg_result($resaco,$iresaco,'ed66_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010136,1008792,'','".AddSlashes(pg_result($resaco,$iresaco,'ed66_i_historicomps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010136,1008793,'','".AddSlashes(pg_result($resaco,$iresaco,'ed66_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010136,1008794,'','".AddSlashes(pg_result($resaco,$iresaco,'ed66_i_anoadap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010136,1008795,'','".AddSlashes(pg_result($resaco,$iresaco,'ed66_i_periodoadap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010136,1008796,'','".AddSlashes(pg_result($resaco,$iresaco,'ed66_c_tiporesultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010136,1008797,'','".AddSlashes(pg_result($resaco,$iresaco,'ed66_i_qtdch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010136,1008798,'','".AddSlashes(pg_result($resaco,$iresaco,'ed66_t_resultobtido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010136,1008799,'','".AddSlashes(pg_result($resaco,$iresaco,'ed66_c_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010136,1008800,'','".AddSlashes(pg_result($resaco,$iresaco,'ed66_c_tipoadap'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from histmpsadap
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed66_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed66_i_codigo = $ed66_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico MPS Disciplina com Adaptação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed66_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Histórico MPS Disciplina com Adaptação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed66_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed66_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:histmpsadap";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed66_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histmpsadap ";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = histmpsadap.ed66_i_disciplina";
     $sql  .="      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      inner join historicomps  on  historicomps.ed62_i_codigo = histmpsadap.ed66_i_historicomps";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = historicomps.ed62_i_escola";
     $sql .= "      inner join justificativa  on  justificativa.ed06_i_codigo = historicomps.ed62_i_justificativa";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = historicomps.ed62_i_serie";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = historicomps.ed62_i_turma";
     $sql .= "      inner join historico  as a on   a.ed61_i_codigo = historicomps.ed62_i_historico";
     $sql2 = "";
     if($dbwhere==""){
       if($ed66_i_codigo!=null ){
         $sql2 .= " where histmpsadap.ed66_i_codigo = $ed66_i_codigo "; 
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
   function sql_query_file ( $ed66_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from histmpsadap ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed66_i_codigo!=null ){
         $sql2 .= " where histmpsadap.ed66_i_codigo = $ed66_i_codigo "; 
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