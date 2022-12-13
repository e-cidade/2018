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

//MODULO: Farmacia
//CLASSE DA ENTIDADE far_farmacia
class cl_far_farmacia { 
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
   var $fa13_i_codigo = 0; 
   var $fa13_i_departamento = 0; 
   var $fa13_c_autosanitaria = null; 
   var $fa13_c_inscestadual = null; 
   var $fa13_c_resptecnico = null; 
   var $fa13_c_crf = null; 
   var $fa13_c_cnpj = null; 
   var $fa13_c_numlicenca = null; 
   var $fa13_c_regiao = null; 
   var $fa13_i_farprof = 0; 
   var $fa13_c_inscmf = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa13_i_codigo = int4 = Código 
                 fa13_i_departamento = int4 = Departamento 
                 fa13_c_autosanitaria = char(50) = Autoridade Sanitária 
                 fa13_c_inscestadual = char(10) = Inscrição Estadual 
                 fa13_c_resptecnico = char(50) = Responsável técnico 
                 fa13_c_crf = char(15) = CRF 
                 fa13_c_cnpj = char(15) = CNPJ 
                 fa13_c_numlicenca = char(15) = Número da Licença 
                 fa13_c_regiao = char(10) = Região 
                 fa13_i_farprof = int4 = Farmacêutico 
                 fa13_c_inscmf = char(15) = Incrição Ministério da Fazenda 
                 ";
   //funcao construtor da classe 
   function cl_far_farmacia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_farmacia"); 
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
       $this->fa13_i_codigo = ($this->fa13_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa13_i_codigo"]:$this->fa13_i_codigo);
       $this->fa13_i_departamento = ($this->fa13_i_departamento == ""?@$GLOBALS["HTTP_POST_VARS"]["fa13_i_departamento"]:$this->fa13_i_departamento);
       $this->fa13_c_autosanitaria = ($this->fa13_c_autosanitaria == ""?@$GLOBALS["HTTP_POST_VARS"]["fa13_c_autosanitaria"]:$this->fa13_c_autosanitaria);
       $this->fa13_c_inscestadual = ($this->fa13_c_inscestadual == ""?@$GLOBALS["HTTP_POST_VARS"]["fa13_c_inscestadual"]:$this->fa13_c_inscestadual);
       $this->fa13_c_resptecnico = ($this->fa13_c_resptecnico == ""?@$GLOBALS["HTTP_POST_VARS"]["fa13_c_resptecnico"]:$this->fa13_c_resptecnico);
       $this->fa13_c_crf = ($this->fa13_c_crf == ""?@$GLOBALS["HTTP_POST_VARS"]["fa13_c_crf"]:$this->fa13_c_crf);
       $this->fa13_c_cnpj = ($this->fa13_c_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["fa13_c_cnpj"]:$this->fa13_c_cnpj);
       $this->fa13_c_numlicenca = ($this->fa13_c_numlicenca == ""?@$GLOBALS["HTTP_POST_VARS"]["fa13_c_numlicenca"]:$this->fa13_c_numlicenca);
       $this->fa13_c_regiao = ($this->fa13_c_regiao == ""?@$GLOBALS["HTTP_POST_VARS"]["fa13_c_regiao"]:$this->fa13_c_regiao);
       $this->fa13_i_farprof = ($this->fa13_i_farprof == ""?@$GLOBALS["HTTP_POST_VARS"]["fa13_i_farprof"]:$this->fa13_i_farprof);
       $this->fa13_c_inscmf = ($this->fa13_c_inscmf == ""?@$GLOBALS["HTTP_POST_VARS"]["fa13_c_inscmf"]:$this->fa13_c_inscmf);
     }else{
       $this->fa13_i_codigo = ($this->fa13_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa13_i_codigo"]:$this->fa13_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa13_i_codigo){ 
      $this->atualizacampos();
     if($this->fa13_i_departamento == null ){ 
       $this->erro_sql = " Campo Departamento nao Informado.";
       $this->erro_campo = "fa13_i_departamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa13_c_autosanitaria == null ){ 
       $this->erro_sql = " Campo Autoridade Sanitária nao Informado.";
       $this->erro_campo = "fa13_c_autosanitaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa13_c_inscestadual == null ){ 
       $this->erro_sql = " Campo Inscrição Estadual nao Informado.";
       $this->erro_campo = "fa13_c_inscestadual";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa13_c_resptecnico == null ){ 
       $this->erro_sql = " Campo Responsável técnico nao Informado.";
       $this->erro_campo = "fa13_c_resptecnico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa13_c_crf == null ){ 
       $this->erro_sql = " Campo CRF nao Informado.";
       $this->erro_campo = "fa13_c_crf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa13_c_cnpj == null ){ 
       $this->erro_sql = " Campo CNPJ nao Informado.";
       $this->erro_campo = "fa13_c_cnpj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa13_c_numlicenca == null ){ 
       $this->erro_sql = " Campo Número da Licença nao Informado.";
       $this->erro_campo = "fa13_c_numlicenca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa13_c_regiao == null ){ 
       $this->erro_sql = " Campo Região nao Informado.";
       $this->erro_campo = "fa13_c_regiao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa13_i_farprof == null ){ 
       $this->erro_sql = " Campo Farmacêutico nao Informado.";
       $this->erro_campo = "fa13_i_farprof";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa13_c_inscmf == null ){ 
       $this->erro_sql = " Campo Incrição Ministério da Fazenda nao Informado.";
       $this->erro_campo = "fa13_c_inscmf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa13_i_codigo == "" || $fa13_i_codigo == null ){
       $result = db_query("select nextval('far_farmacia_fa13_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: far_farmacia_fa13_codigo_seq do campo: fa13_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa13_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from far_farmacia_fa13_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa13_i_codigo)){
         $this->erro_sql = " Campo fa13_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa13_i_codigo = $fa13_i_codigo; 
       }
     }
     if(($this->fa13_i_codigo == null) || ($this->fa13_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa13_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_farmacia(
                                       fa13_i_codigo 
                                      ,fa13_i_departamento 
                                      ,fa13_c_autosanitaria 
                                      ,fa13_c_inscestadual 
                                      ,fa13_c_resptecnico 
                                      ,fa13_c_crf 
                                      ,fa13_c_cnpj 
                                      ,fa13_c_numlicenca 
                                      ,fa13_c_regiao 
                                      ,fa13_i_farprof 
                                      ,fa13_c_inscmf 
                       )
                values (
                                $this->fa13_i_codigo 
                               ,$this->fa13_i_departamento 
                               ,'$this->fa13_c_autosanitaria' 
                               ,'$this->fa13_c_inscestadual' 
                               ,'$this->fa13_c_resptecnico' 
                               ,'$this->fa13_c_crf' 
                               ,'$this->fa13_c_cnpj' 
                               ,'$this->fa13_c_numlicenca' 
                               ,'$this->fa13_c_regiao' 
                               ,$this->fa13_i_farprof 
                               ,'$this->fa13_c_inscmf' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_farmacia ($this->fa13_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_farmacia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_farmacia ($this->fa13_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa13_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa13_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14005,'$this->fa13_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2461,14005,'','".AddSlashes(pg_result($resaco,0,'fa13_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2461,14006,'','".AddSlashes(pg_result($resaco,0,'fa13_i_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2461,14007,'','".AddSlashes(pg_result($resaco,0,'fa13_c_autosanitaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2461,14008,'','".AddSlashes(pg_result($resaco,0,'fa13_c_inscestadual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2461,14009,'','".AddSlashes(pg_result($resaco,0,'fa13_c_resptecnico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2461,14010,'','".AddSlashes(pg_result($resaco,0,'fa13_c_crf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2461,14011,'','".AddSlashes(pg_result($resaco,0,'fa13_c_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2461,14012,'','".AddSlashes(pg_result($resaco,0,'fa13_c_numlicenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2461,14013,'','".AddSlashes(pg_result($resaco,0,'fa13_c_regiao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2461,14097,'','".AddSlashes(pg_result($resaco,0,'fa13_i_farprof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2461,14098,'','".AddSlashes(pg_result($resaco,0,'fa13_c_inscmf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa13_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_farmacia set ";
     $virgula = "";
     if(trim($this->fa13_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa13_i_codigo"])){ 
       $sql  .= $virgula." fa13_i_codigo = $this->fa13_i_codigo ";
       $virgula = ",";
       if(trim($this->fa13_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "fa13_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa13_i_departamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa13_i_departamento"])){ 
       $sql  .= $virgula." fa13_i_departamento = $this->fa13_i_departamento ";
       $virgula = ",";
       if(trim($this->fa13_i_departamento) == null ){ 
         $this->erro_sql = " Campo Departamento nao Informado.";
         $this->erro_campo = "fa13_i_departamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa13_c_autosanitaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_autosanitaria"])){ 
       $sql  .= $virgula." fa13_c_autosanitaria = '$this->fa13_c_autosanitaria' ";
       $virgula = ",";
       if(trim($this->fa13_c_autosanitaria) == null ){ 
         $this->erro_sql = " Campo Autoridade Sanitária nao Informado.";
         $this->erro_campo = "fa13_c_autosanitaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa13_c_inscestadual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_inscestadual"])){ 
       $sql  .= $virgula." fa13_c_inscestadual = '$this->fa13_c_inscestadual' ";
       $virgula = ",";
       if(trim($this->fa13_c_inscestadual) == null ){ 
         $this->erro_sql = " Campo Inscrição Estadual nao Informado.";
         $this->erro_campo = "fa13_c_inscestadual";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa13_c_resptecnico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_resptecnico"])){ 
       $sql  .= $virgula." fa13_c_resptecnico = '$this->fa13_c_resptecnico' ";
       $virgula = ",";
       if(trim($this->fa13_c_resptecnico) == null ){ 
         $this->erro_sql = " Campo Responsável técnico nao Informado.";
         $this->erro_campo = "fa13_c_resptecnico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa13_c_crf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_crf"])){ 
       $sql  .= $virgula." fa13_c_crf = '$this->fa13_c_crf' ";
       $virgula = ",";
       if(trim($this->fa13_c_crf) == null ){ 
         $this->erro_sql = " Campo CRF nao Informado.";
         $this->erro_campo = "fa13_c_crf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa13_c_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_cnpj"])){ 
       $sql  .= $virgula." fa13_c_cnpj = '$this->fa13_c_cnpj' ";
       $virgula = ",";
       if(trim($this->fa13_c_cnpj) == null ){ 
         $this->erro_sql = " Campo CNPJ nao Informado.";
         $this->erro_campo = "fa13_c_cnpj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa13_c_numlicenca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_numlicenca"])){ 
       $sql  .= $virgula." fa13_c_numlicenca = '$this->fa13_c_numlicenca' ";
       $virgula = ",";
       if(trim($this->fa13_c_numlicenca) == null ){ 
         $this->erro_sql = " Campo Número da Licença nao Informado.";
         $this->erro_campo = "fa13_c_numlicenca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa13_c_regiao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_regiao"])){ 
       $sql  .= $virgula." fa13_c_regiao = '$this->fa13_c_regiao' ";
       $virgula = ",";
       if(trim($this->fa13_c_regiao) == null ){ 
         $this->erro_sql = " Campo Região nao Informado.";
         $this->erro_campo = "fa13_c_regiao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa13_i_farprof)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa13_i_farprof"])){ 
       $sql  .= $virgula." fa13_i_farprof = $this->fa13_i_farprof ";
       $virgula = ",";
       if(trim($this->fa13_i_farprof) == null ){ 
         $this->erro_sql = " Campo Farmacêutico nao Informado.";
         $this->erro_campo = "fa13_i_farprof";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa13_c_inscmf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_inscmf"])){ 
       $sql  .= $virgula." fa13_c_inscmf = '$this->fa13_c_inscmf' ";
       $virgula = ",";
       if(trim($this->fa13_c_inscmf) == null ){ 
         $this->erro_sql = " Campo Incrição Ministério da Fazenda nao Informado.";
         $this->erro_campo = "fa13_c_inscmf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa13_i_codigo!=null){
       $sql .= " fa13_i_codigo = $this->fa13_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa13_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14005,'$this->fa13_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa13_i_codigo"]) || $this->fa13_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2461,14005,'".AddSlashes(pg_result($resaco,$conresaco,'fa13_i_codigo'))."','$this->fa13_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa13_i_departamento"]) || $this->fa13_i_departamento != "")
           $resac = db_query("insert into db_acount values($acount,2461,14006,'".AddSlashes(pg_result($resaco,$conresaco,'fa13_i_departamento'))."','$this->fa13_i_departamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_autosanitaria"]) || $this->fa13_c_autosanitaria != "")
           $resac = db_query("insert into db_acount values($acount,2461,14007,'".AddSlashes(pg_result($resaco,$conresaco,'fa13_c_autosanitaria'))."','$this->fa13_c_autosanitaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_inscestadual"]) || $this->fa13_c_inscestadual != "")
           $resac = db_query("insert into db_acount values($acount,2461,14008,'".AddSlashes(pg_result($resaco,$conresaco,'fa13_c_inscestadual'))."','$this->fa13_c_inscestadual',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_resptecnico"]) || $this->fa13_c_resptecnico != "")
           $resac = db_query("insert into db_acount values($acount,2461,14009,'".AddSlashes(pg_result($resaco,$conresaco,'fa13_c_resptecnico'))."','$this->fa13_c_resptecnico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_crf"]) || $this->fa13_c_crf != "")
           $resac = db_query("insert into db_acount values($acount,2461,14010,'".AddSlashes(pg_result($resaco,$conresaco,'fa13_c_crf'))."','$this->fa13_c_crf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_cnpj"]) || $this->fa13_c_cnpj != "")
           $resac = db_query("insert into db_acount values($acount,2461,14011,'".AddSlashes(pg_result($resaco,$conresaco,'fa13_c_cnpj'))."','$this->fa13_c_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_numlicenca"]) || $this->fa13_c_numlicenca != "")
           $resac = db_query("insert into db_acount values($acount,2461,14012,'".AddSlashes(pg_result($resaco,$conresaco,'fa13_c_numlicenca'))."','$this->fa13_c_numlicenca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_regiao"]) || $this->fa13_c_regiao != "")
           $resac = db_query("insert into db_acount values($acount,2461,14013,'".AddSlashes(pg_result($resaco,$conresaco,'fa13_c_regiao'))."','$this->fa13_c_regiao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa13_i_farprof"]) || $this->fa13_i_farprof != "")
           $resac = db_query("insert into db_acount values($acount,2461,14097,'".AddSlashes(pg_result($resaco,$conresaco,'fa13_i_farprof'))."','$this->fa13_i_farprof',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa13_c_inscmf"]) || $this->fa13_c_inscmf != "")
           $resac = db_query("insert into db_acount values($acount,2461,14098,'".AddSlashes(pg_result($resaco,$conresaco,'fa13_c_inscmf'))."','$this->fa13_c_inscmf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_farmacia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa13_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_farmacia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa13_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa13_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14005,'$fa13_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2461,14005,'','".AddSlashes(pg_result($resaco,$iresaco,'fa13_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2461,14006,'','".AddSlashes(pg_result($resaco,$iresaco,'fa13_i_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2461,14007,'','".AddSlashes(pg_result($resaco,$iresaco,'fa13_c_autosanitaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2461,14008,'','".AddSlashes(pg_result($resaco,$iresaco,'fa13_c_inscestadual'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2461,14009,'','".AddSlashes(pg_result($resaco,$iresaco,'fa13_c_resptecnico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2461,14010,'','".AddSlashes(pg_result($resaco,$iresaco,'fa13_c_crf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2461,14011,'','".AddSlashes(pg_result($resaco,$iresaco,'fa13_c_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2461,14012,'','".AddSlashes(pg_result($resaco,$iresaco,'fa13_c_numlicenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2461,14013,'','".AddSlashes(pg_result($resaco,$iresaco,'fa13_c_regiao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2461,14097,'','".AddSlashes(pg_result($resaco,$iresaco,'fa13_i_farprof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2461,14098,'','".AddSlashes(pg_result($resaco,$iresaco,'fa13_c_inscmf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_farmacia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa13_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa13_i_codigo = $fa13_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_farmacia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa13_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_farmacia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa13_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa13_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_farmacia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa13_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_farmacia ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = far_farmacia.fa13_i_departamento";
     $sql .= "      inner join far_farmaceutico  on  far_farmaceutico.fa25_i_codigo = far_farmacia.fa13_i_farprof";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = far_farmaceutico.fa25_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($fa13_i_codigo!=null ){
         $sql2 .= " where far_farmacia.fa13_i_codigo = $fa13_i_codigo "; 
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
   function sql_query_file ( $fa13_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_farmacia ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa13_i_codigo!=null ){
         $sql2 .= " where far_farmacia.fa13_i_codigo = $fa13_i_codigo "; 
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