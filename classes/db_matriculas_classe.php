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
<?
//MODULO: educação
//CLASSE DA ENTIDADE matriculas
class cl_matriculas { 
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
   var $ed09_i_codigo = 0; 
   var $ed09_i_aluno = 0; 
   var $ed09_i_escola = 0; 
   var $ed09_i_serie = 0; 
   var $ed09_d_inicio_dia = null; 
   var $ed09_d_inicio_mes = null; 
   var $ed09_d_inicio_ano = null; 
   var $ed09_d_inicio = null; 
   var $ed09_c_situacao = null; 
   var $ed09_d_termino_dia = null; 
   var $ed09_d_termino_mes = null; 
   var $ed09_d_termino_ano = null; 
   var $ed09_d_termino = null; 
   var $ed09_i_ano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed09_i_codigo = int8 = Matrícula 
                 ed09_i_aluno = int8 = Aluno 
                 ed09_i_escola = int8 = Escola 
                 ed09_i_serie = int8 = Série 
                 ed09_d_inicio = date = Início 
                 ed09_c_situacao = char(20) = Situação 
                 ed09_d_termino = date = Término 
                 ed09_i_ano = int8 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_matriculas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matriculas"); 
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
       $this->ed09_i_codigo = ($this->ed09_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_i_codigo"]:$this->ed09_i_codigo);
       $this->ed09_i_aluno = ($this->ed09_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_i_aluno"]:$this->ed09_i_aluno);
       $this->ed09_i_escola = ($this->ed09_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_i_escola"]:$this->ed09_i_escola);
       $this->ed09_i_serie = ($this->ed09_i_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_i_serie"]:$this->ed09_i_serie);
       if($this->ed09_d_inicio == ""){
         $this->ed09_d_inicio_dia = ($this->ed09_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_d_inicio_dia"]:$this->ed09_d_inicio_dia);
         $this->ed09_d_inicio_mes = ($this->ed09_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_d_inicio_mes"]:$this->ed09_d_inicio_mes);
         $this->ed09_d_inicio_ano = ($this->ed09_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_d_inicio_ano"]:$this->ed09_d_inicio_ano);
         if($this->ed09_d_inicio_dia != ""){
            $this->ed09_d_inicio = $this->ed09_d_inicio_ano."-".$this->ed09_d_inicio_mes."-".$this->ed09_d_inicio_dia;
         }
       }
       $this->ed09_c_situacao = ($this->ed09_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_c_situacao"]:$this->ed09_c_situacao);
       if($this->ed09_d_termino == ""){
         $this->ed09_d_termino_dia = ($this->ed09_d_termino_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_d_termino_dia"]:$this->ed09_d_termino_dia);
         $this->ed09_d_termino_mes = ($this->ed09_d_termino_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_d_termino_mes"]:$this->ed09_d_termino_mes);
         $this->ed09_d_termino_ano = ($this->ed09_d_termino_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_d_termino_ano"]:$this->ed09_d_termino_ano);
         if($this->ed09_d_termino_dia != ""){
            $this->ed09_d_termino = $this->ed09_d_termino_ano."-".$this->ed09_d_termino_mes."-".$this->ed09_d_termino_dia;
         }
       }
       $this->ed09_i_ano = ($this->ed09_i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_i_ano"]:$this->ed09_i_ano);
     }else{
       $this->ed09_i_codigo = ($this->ed09_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed09_i_codigo"]:$this->ed09_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed09_i_codigo){ 
      $this->atualizacampos();
     if($this->ed09_i_aluno == null ){ 
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed09_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed09_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed09_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed09_i_serie == null ){ 
       $this->erro_sql = " Campo Série nao Informado.";
       $this->erro_campo = "ed09_i_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed09_d_inicio == null ){ 
       $this->erro_sql = " Campo Início nao Informado.";
       $this->erro_campo = "ed09_d_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed09_c_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "ed09_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed09_d_termino == null ){ 
       $this->ed09_d_termino = "null";
     }
     if($this->ed09_i_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "ed09_i_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed09_i_codigo == "" || $ed09_i_codigo == null ){
       $result = @pg_query("select nextval('matriculas_ed09_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matriculas_ed09_i_codigo_seq do campo: ed09_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed09_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from matriculas_ed09_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed09_i_codigo)){
         $this->erro_sql = " Campo ed09_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed09_i_codigo = $ed09_i_codigo; 
       }
     }
     if(($this->ed09_i_codigo == null) || ($this->ed09_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed09_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matriculas(
                                       ed09_i_codigo 
                                      ,ed09_i_aluno 
                                      ,ed09_i_escola 
                                      ,ed09_i_serie 
                                      ,ed09_d_inicio 
                                      ,ed09_c_situacao 
                                      ,ed09_d_termino 
                                      ,ed09_i_ano 
                       )
                values (
                                $this->ed09_i_codigo 
                               ,$this->ed09_i_aluno 
                               ,$this->ed09_i_escola 
                               ,$this->ed09_i_serie 
                               ,".($this->ed09_d_inicio == "null" || $this->ed09_d_inicio == ""?"null":"'".$this->ed09_d_inicio."'")." 
                               ,'$this->ed09_c_situacao' 
                               ,".($this->ed09_d_termino == "null" || $this->ed09_d_termino == ""?"null":"'".$this->ed09_d_termino."'")." 
                               ,$this->ed09_i_ano 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Matrículas ($this->ed09_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Matrículas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Matrículas ($this->ed09_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed09_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed09_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1005016,'$this->ed09_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1005009,1005016,'','".AddSlashes(pg_result($resaco,0,'ed09_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005009,1006045,'','".AddSlashes(pg_result($resaco,0,'ed09_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005009,1006302,'','".AddSlashes(pg_result($resaco,0,'ed09_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005009,1006047,'','".AddSlashes(pg_result($resaco,0,'ed09_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005009,1005017,'','".AddSlashes(pg_result($resaco,0,'ed09_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005009,1005018,'','".AddSlashes(pg_result($resaco,0,'ed09_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005009,1005019,'','".AddSlashes(pg_result($resaco,0,'ed09_d_termino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1005009,1006159,'','".AddSlashes(pg_result($resaco,0,'ed09_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed09_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update matriculas set ";
     $virgula = "";
     if(trim($this->ed09_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed09_i_codigo"])){ 
       $sql  .= $virgula." ed09_i_codigo = $this->ed09_i_codigo ";
       $virgula = ",";
       if(trim($this->ed09_i_codigo) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed09_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed09_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed09_i_aluno"])){ 
       $sql  .= $virgula." ed09_i_aluno = $this->ed09_i_aluno ";
       $virgula = ",";
       if(trim($this->ed09_i_aluno) == null ){ 
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed09_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed09_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed09_i_escola"])){ 
       $sql  .= $virgula." ed09_i_escola = $this->ed09_i_escola ";
       $virgula = ",";
       if(trim($this->ed09_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed09_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed09_i_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed09_i_serie"])){ 
       $sql  .= $virgula." ed09_i_serie = $this->ed09_i_serie ";
       $virgula = ",";
       if(trim($this->ed09_i_serie) == null ){ 
         $this->erro_sql = " Campo Série nao Informado.";
         $this->erro_campo = "ed09_i_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed09_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed09_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed09_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." ed09_d_inicio = '$this->ed09_d_inicio' ";
       $virgula = ",";
       if(trim($this->ed09_d_inicio) == null ){ 
         $this->erro_sql = " Campo Início nao Informado.";
         $this->erro_campo = "ed09_d_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed09_d_inicio_dia"])){ 
         $sql  .= $virgula." ed09_d_inicio = null ";
         $virgula = ",";
         if(trim($this->ed09_d_inicio) == null ){ 
           $this->erro_sql = " Campo Início nao Informado.";
           $this->erro_campo = "ed09_d_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed09_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed09_c_situacao"])){ 
       $sql  .= $virgula." ed09_c_situacao = '$this->ed09_c_situacao' ";
       $virgula = ",";
       if(trim($this->ed09_c_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "ed09_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed09_d_termino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed09_d_termino_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed09_d_termino_dia"] !="") ){ 
       $sql  .= $virgula." ed09_d_termino = '$this->ed09_d_termino' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed09_d_termino_dia"])){ 
         $sql  .= $virgula." ed09_d_termino = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed09_i_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed09_i_ano"])){ 
       $sql  .= $virgula." ed09_i_ano = $this->ed09_i_ano ";
       $virgula = ",";
       if(trim($this->ed09_i_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "ed09_i_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed09_i_codigo!=null){
       $sql .= " ed09_i_codigo = $this->ed09_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed09_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1005016,'$this->ed09_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed09_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1005009,1005016,'".AddSlashes(pg_result($resaco,$conresaco,'ed09_i_codigo'))."','$this->ed09_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed09_i_aluno"]))
           $resac = pg_query("insert into db_acount values($acount,1005009,1006045,'".AddSlashes(pg_result($resaco,$conresaco,'ed09_i_aluno'))."','$this->ed09_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed09_i_escola"]))
           $resac = pg_query("insert into db_acount values($acount,1005009,1006302,'".AddSlashes(pg_result($resaco,$conresaco,'ed09_i_escola'))."','$this->ed09_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed09_i_serie"]))
           $resac = pg_query("insert into db_acount values($acount,1005009,1006047,'".AddSlashes(pg_result($resaco,$conresaco,'ed09_i_serie'))."','$this->ed09_i_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed09_d_inicio"]))
           $resac = pg_query("insert into db_acount values($acount,1005009,1005017,'".AddSlashes(pg_result($resaco,$conresaco,'ed09_d_inicio'))."','$this->ed09_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed09_c_situacao"]))
           $resac = pg_query("insert into db_acount values($acount,1005009,1005018,'".AddSlashes(pg_result($resaco,$conresaco,'ed09_c_situacao'))."','$this->ed09_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed09_d_termino"]))
           $resac = pg_query("insert into db_acount values($acount,1005009,1005019,'".AddSlashes(pg_result($resaco,$conresaco,'ed09_d_termino'))."','$this->ed09_d_termino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed09_i_ano"]))
           $resac = pg_query("insert into db_acount values($acount,1005009,1006159,'".AddSlashes(pg_result($resaco,$conresaco,'ed09_i_ano'))."','$this->ed09_i_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matrículas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed09_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matrículas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed09_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed09_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed09_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed09_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1005016,'$ed09_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1005009,1005016,'','".AddSlashes(pg_result($resaco,$iresaco,'ed09_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005009,1006045,'','".AddSlashes(pg_result($resaco,$iresaco,'ed09_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005009,1006302,'','".AddSlashes(pg_result($resaco,$iresaco,'ed09_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005009,1006047,'','".AddSlashes(pg_result($resaco,$iresaco,'ed09_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005009,1005017,'','".AddSlashes(pg_result($resaco,$iresaco,'ed09_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005009,1005018,'','".AddSlashes(pg_result($resaco,$iresaco,'ed09_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005009,1005019,'','".AddSlashes(pg_result($resaco,$iresaco,'ed09_d_termino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1005009,1006159,'','".AddSlashes(pg_result($resaco,$iresaco,'ed09_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matriculas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed09_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed09_i_codigo = $ed09_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matrículas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed09_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matrículas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed09_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed09_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:matriculas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed09_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matriculas ";
     $sql .= "      inner join escolas  on  escolas.ed02_i_codigo = matriculas.ed09_i_escola";
     $sql .= "      inner join series  on  series.ed03_i_codigo = matriculas.ed09_i_serie";
     $sql .= "      inner join alunos  on  alunos.ed07_i_codigo = matriculas.ed09_i_aluno";
     //$sql .= "      inner join cgm  on  cgm.z01_numcgm = escolas.ed02_i_codigo";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escolas.ed02_i_departamento";
     //$sql .= "      inner join cgm  as a on   a.z01_numcgm = alunos.ed07_i_codigo and   a.z01_numcgm = alunos.ed07_i_responsavel";
     $sql2 = "";
     if($dbwhere==""){
       if($ed09_i_codigo!=null ){
         $sql2 .= " where matriculas.ed09_i_codigo = $ed09_i_codigo "; 
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
   function sql_query_file ( $ed09_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matriculas ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed09_i_codigo!=null ){
         $sql2 .= " where matriculas.ed09_i_codigo = $ed09_i_codigo "; 
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