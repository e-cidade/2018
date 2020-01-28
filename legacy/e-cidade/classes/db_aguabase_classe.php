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

//MODULO: agua
//CLASSE DA ENTIDADE aguabase
class cl_aguabase { 
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
   var $x01_matric = 0; 
   var $x01_codrua = 0; 
   var $x01_codbairro = 0; 
   var $x01_numcgm = 0; 
   var $x01_promit = 0; 
   var $x01_distrito = 0; 
   var $x01_zona = 0; 
   var $x01_quadra = 0; 
   var $x01_numero = 0; 
   var $x01_orientacao = null; 
   var $x01_rota = 0; 
   var $x01_dtcadastro_dia = null; 
   var $x01_dtcadastro_mes = null; 
   var $x01_dtcadastro_ano = null; 
   var $x01_dtcadastro = null; 
   var $x01_qtdponto = 0; 
   var $x01_obs = null; 
   var $x01_multiplicador = 'f'; 
   var $x01_qtdeconomia = 0; 
   var $x01_entrega = 0; 
   var $x01_letra = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x01_matric = int4 = Matrícula 
                 x01_codrua = int4 = Logradouro 
                 x01_codbairro = int4 = Bairro 
                 x01_numcgm = int4 = Proprietário 
                 x01_promit = int4 = Promitente 
                 x01_distrito = int4 = Distrito 
                 x01_zona = int4 = Zona Fiscal 
                 x01_quadra = int4 = Quadra 
                 x01_numero = int4 = Número 
                 x01_orientacao = varchar(10) = orientação 
                 x01_rota = int4 = Rota 
                 x01_dtcadastro = date = Cadastro 
                 x01_qtdponto = int4 = Pontos 
                 x01_obs = text = Observações 
                 x01_multiplicador = bool = Multiplica Economias 
                 x01_qtdeconomia = int4 = Economias 
                 x01_entrega = int4 = Zona Entrega 
                 x01_letra = char(1) = Letra 
                 ";
   //funcao construtor da classe 
   function cl_aguabase() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguabase"); 
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
       $this->x01_matric = ($this->x01_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_matric"]:$this->x01_matric);
       $this->x01_codrua = ($this->x01_codrua == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_codrua"]:$this->x01_codrua);
       $this->x01_codbairro = ($this->x01_codbairro == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_codbairro"]:$this->x01_codbairro);
       $this->x01_numcgm = ($this->x01_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_numcgm"]:$this->x01_numcgm);
       $this->x01_promit = ($this->x01_promit == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_promit"]:$this->x01_promit);
       $this->x01_distrito = ($this->x01_distrito == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_distrito"]:$this->x01_distrito);
       $this->x01_zona = ($this->x01_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_zona"]:$this->x01_zona);
       $this->x01_quadra = ($this->x01_quadra == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_quadra"]:$this->x01_quadra);
       $this->x01_numero = ($this->x01_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_numero"]:$this->x01_numero);
       $this->x01_orientacao = ($this->x01_orientacao == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_orientacao"]:$this->x01_orientacao);
       $this->x01_rota = ($this->x01_rota == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_rota"]:$this->x01_rota);
       if($this->x01_dtcadastro == ""){
         $this->x01_dtcadastro_dia = ($this->x01_dtcadastro_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_dtcadastro_dia"]:$this->x01_dtcadastro_dia);
         $this->x01_dtcadastro_mes = ($this->x01_dtcadastro_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_dtcadastro_mes"]:$this->x01_dtcadastro_mes);
         $this->x01_dtcadastro_ano = ($this->x01_dtcadastro_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_dtcadastro_ano"]:$this->x01_dtcadastro_ano);
         if($this->x01_dtcadastro_dia != ""){
            $this->x01_dtcadastro = $this->x01_dtcadastro_ano."-".$this->x01_dtcadastro_mes."-".$this->x01_dtcadastro_dia;
         }
       }
       $this->x01_qtdponto = ($this->x01_qtdponto == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_qtdponto"]:$this->x01_qtdponto);
       $this->x01_obs = ($this->x01_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_obs"]:$this->x01_obs);
       $this->x01_multiplicador = ($this->x01_multiplicador == "f"?@$GLOBALS["HTTP_POST_VARS"]["x01_multiplicador"]:$this->x01_multiplicador);
       $this->x01_qtdeconomia = ($this->x01_qtdeconomia == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_qtdeconomia"]:$this->x01_qtdeconomia);
       $this->x01_entrega = ($this->x01_entrega == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_entrega"]:$this->x01_entrega);
       $this->x01_letra = ($this->x01_letra == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_letra"]:$this->x01_letra);
     }else{
       $this->x01_matric = ($this->x01_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x01_matric"]:$this->x01_matric);
     }
   }
   // funcao para inclusao
   function incluir ($x01_matric){ 
      $this->atualizacampos();
     if($this->x01_codrua == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "x01_codrua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x01_codbairro == null ){ 
       $this->erro_sql = " Campo Bairro nao Informado.";
       $this->erro_campo = "x01_codbairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x01_numcgm == null ){ 
       $this->erro_sql = " Campo Proprietário nao Informado.";
       $this->erro_campo = "x01_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x01_promit == null ){ 
       $this->x01_promit = "0";
     }
     if($this->x01_distrito == null ){ 
       $this->erro_sql = " Campo Distrito nao Informado.";
       $this->erro_campo = "x01_distrito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x01_zona == null ){ 
       $this->erro_sql = " Campo Zona Fiscal nao Informado.";
       $this->erro_campo = "x01_zona";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x01_quadra == null ){ 
       $this->erro_sql = " Campo Quadra nao Informado.";
       $this->erro_campo = "x01_quadra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x01_numero == null ){ 
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "x01_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x01_rota == null ){ 
       $this->erro_sql = " Campo Rota nao Informado.";
       $this->erro_campo = "x01_rota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x01_dtcadastro == null ){ 
       $this->erro_sql = " Campo Cadastro nao Informado.";
       $this->erro_campo = "x01_dtcadastro_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x01_qtdponto == null ){ 
       $this->erro_sql = " Campo Pontos nao Informado.";
       $this->erro_campo = "x01_qtdponto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x01_multiplicador == null ){ 
       $this->erro_sql = " Campo Multiplica Economias nao Informado.";
       $this->erro_campo = "x01_multiplicador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x01_qtdeconomia == null ){ 
       $this->erro_sql = " Campo Economias nao Informado.";
       $this->erro_campo = "x01_qtdeconomia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x01_entrega == null ){ 
       $this->erro_sql = " Campo Zona Entrega nao Informado.";
       $this->erro_campo = "x01_entrega";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->x01_matric = $x01_matric; 
     if(($this->x01_matric == null) || ($this->x01_matric == "") ){ 
       $this->erro_sql = " Campo x01_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguabase(
                                       x01_matric 
                                      ,x01_codrua 
                                      ,x01_codbairro 
                                      ,x01_numcgm 
                                      ,x01_promit 
                                      ,x01_distrito 
                                      ,x01_zona 
                                      ,x01_quadra 
                                      ,x01_numero 
                                      ,x01_orientacao 
                                      ,x01_rota 
                                      ,x01_dtcadastro 
                                      ,x01_qtdponto 
                                      ,x01_obs 
                                      ,x01_multiplicador 
                                      ,x01_qtdeconomia 
                                      ,x01_entrega 
                                      ,x01_letra 
                       )
                values (
                                $this->x01_matric 
                               ,$this->x01_codrua 
                               ,$this->x01_codbairro 
                               ,$this->x01_numcgm 
                               ,$this->x01_promit 
                               ,$this->x01_distrito 
                               ,$this->x01_zona 
                               ,$this->x01_quadra 
                               ,$this->x01_numero 
                               ,'$this->x01_orientacao' 
                               ,$this->x01_rota 
                               ,".($this->x01_dtcadastro == "null" || $this->x01_dtcadastro == ""?"null":"'".$this->x01_dtcadastro."'")." 
                               ,$this->x01_qtdponto 
                               ,'$this->x01_obs' 
                               ,'$this->x01_multiplicador' 
                               ,$this->x01_qtdeconomia 
                               ,$this->x01_entrega 
                               ,'$this->x01_letra' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguabase ($this->x01_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguabase já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguabase ($this->x01_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x01_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x01_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8386,'$this->x01_matric','I')");
       $resac = db_query("insert into db_acount values($acount,1426,8386,'','".AddSlashes(pg_result($resaco,0,'x01_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8387,'','".AddSlashes(pg_result($resaco,0,'x01_codrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8388,'','".AddSlashes(pg_result($resaco,0,'x01_codbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8389,'','".AddSlashes(pg_result($resaco,0,'x01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8690,'','".AddSlashes(pg_result($resaco,0,'x01_promit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8390,'','".AddSlashes(pg_result($resaco,0,'x01_distrito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8391,'','".AddSlashes(pg_result($resaco,0,'x01_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8392,'','".AddSlashes(pg_result($resaco,0,'x01_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8393,'','".AddSlashes(pg_result($resaco,0,'x01_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8394,'','".AddSlashes(pg_result($resaco,0,'x01_orientacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8395,'','".AddSlashes(pg_result($resaco,0,'x01_rota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8396,'','".AddSlashes(pg_result($resaco,0,'x01_dtcadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8397,'','".AddSlashes(pg_result($resaco,0,'x01_qtdponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8398,'','".AddSlashes(pg_result($resaco,0,'x01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8441,'','".AddSlashes(pg_result($resaco,0,'x01_multiplicador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8443,'','".AddSlashes(pg_result($resaco,0,'x01_qtdeconomia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8530,'','".AddSlashes(pg_result($resaco,0,'x01_entrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1426,8709,'','".AddSlashes(pg_result($resaco,0,'x01_letra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x01_matric=null) { 
      $this->atualizacampos();
     $sql = " update aguabase set ";
     $virgula = "";
     if(trim($this->x01_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_matric"])){ 
       $sql  .= $virgula." x01_matric = $this->x01_matric ";
       $virgula = ",";
       if(trim($this->x01_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "x01_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_codrua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_codrua"])){ 
       $sql  .= $virgula." x01_codrua = $this->x01_codrua ";
       $virgula = ",";
       if(trim($this->x01_codrua) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "x01_codrua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_codbairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_codbairro"])){ 
       $sql  .= $virgula." x01_codbairro = $this->x01_codbairro ";
       $virgula = ",";
       if(trim($this->x01_codbairro) == null ){ 
         $this->erro_sql = " Campo Bairro nao Informado.";
         $this->erro_campo = "x01_codbairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_numcgm"])){ 
       $sql  .= $virgula." x01_numcgm = $this->x01_numcgm ";
       $virgula = ",";
       if(trim($this->x01_numcgm) == null ){ 
         $this->erro_sql = " Campo Proprietário nao Informado.";
         $this->erro_campo = "x01_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_promit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_promit"])){ 
        if(trim($this->x01_promit)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x01_promit"])){ 
           $this->x01_promit = "0" ; 
        } 
       $sql  .= $virgula." x01_promit = $this->x01_promit ";
       $virgula = ",";
     }
     if(trim($this->x01_distrito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_distrito"])){ 
       $sql  .= $virgula." x01_distrito = $this->x01_distrito ";
       $virgula = ",";
       if(trim($this->x01_distrito) == null ){ 
         $this->erro_sql = " Campo Distrito nao Informado.";
         $this->erro_campo = "x01_distrito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_zona"])){ 
       $sql  .= $virgula." x01_zona = $this->x01_zona ";
       $virgula = ",";
       if(trim($this->x01_zona) == null ){ 
         $this->erro_sql = " Campo Zona Fiscal nao Informado.";
         $this->erro_campo = "x01_zona";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_quadra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_quadra"])){ 
       $sql  .= $virgula." x01_quadra = $this->x01_quadra ";
       $virgula = ",";
       if(trim($this->x01_quadra) == null ){ 
         $this->erro_sql = " Campo Quadra nao Informado.";
         $this->erro_campo = "x01_quadra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_numero"])){ 
       $sql  .= $virgula." x01_numero = $this->x01_numero ";
       $virgula = ",";
       if(trim($this->x01_numero) == null ){ 
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "x01_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_orientacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_orientacao"])){ 
       $sql  .= $virgula." x01_orientacao = '$this->x01_orientacao' ";
       $virgula = ",";
     }
     if(trim($this->x01_rota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_rota"])){ 
       $sql  .= $virgula." x01_rota = $this->x01_rota ";
       $virgula = ",";
       if(trim($this->x01_rota) == null ){ 
         $this->erro_sql = " Campo Rota nao Informado.";
         $this->erro_campo = "x01_rota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_dtcadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_dtcadastro_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x01_dtcadastro_dia"] !="") ){ 
       $sql  .= $virgula." x01_dtcadastro = '$this->x01_dtcadastro' ";
       $virgula = ",";
       if(trim($this->x01_dtcadastro) == null ){ 
         $this->erro_sql = " Campo Cadastro nao Informado.";
         $this->erro_campo = "x01_dtcadastro_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x01_dtcadastro_dia"])){ 
         $sql  .= $virgula." x01_dtcadastro = null ";
         $virgula = ",";
         if(trim($this->x01_dtcadastro) == null ){ 
           $this->erro_sql = " Campo Cadastro nao Informado.";
           $this->erro_campo = "x01_dtcadastro_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x01_qtdponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_qtdponto"])){ 
       $sql  .= $virgula." x01_qtdponto = $this->x01_qtdponto ";
       $virgula = ",";
       if(trim($this->x01_qtdponto) == null ){ 
         $this->erro_sql = " Campo Pontos nao Informado.";
         $this->erro_campo = "x01_qtdponto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_obs"])){ 
       $sql  .= $virgula." x01_obs = '$this->x01_obs' ";
       $virgula = ",";
     }
     if(trim($this->x01_multiplicador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_multiplicador"])){ 
       $sql  .= $virgula." x01_multiplicador = '$this->x01_multiplicador' ";
       $virgula = ",";
       if(trim($this->x01_multiplicador) == null ){ 
         $this->erro_sql = " Campo Multiplica Economias nao Informado.";
         $this->erro_campo = "x01_multiplicador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_qtdeconomia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_qtdeconomia"])){ 
       $sql  .= $virgula." x01_qtdeconomia = $this->x01_qtdeconomia ";
       $virgula = ",";
       if(trim($this->x01_qtdeconomia) == null ){ 
         $this->erro_sql = " Campo Economias nao Informado.";
         $this->erro_campo = "x01_qtdeconomia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_entrega)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_entrega"])){ 
       $sql  .= $virgula." x01_entrega = $this->x01_entrega ";
       $virgula = ",";
       if(trim($this->x01_entrega) == null ){ 
         $this->erro_sql = " Campo Zona Entrega nao Informado.";
         $this->erro_campo = "x01_entrega";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x01_letra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x01_letra"])){ 
       $sql  .= $virgula." x01_letra = '$this->x01_letra' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($x01_matric!=null){
       $sql .= " x01_matric = $this->x01_matric";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x01_matric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8386,'$this->x01_matric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_matric"]))
           $resac = db_query("insert into db_acount values($acount,1426,8386,'".AddSlashes(pg_result($resaco,$conresaco,'x01_matric'))."','$this->x01_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_codrua"]))
           $resac = db_query("insert into db_acount values($acount,1426,8387,'".AddSlashes(pg_result($resaco,$conresaco,'x01_codrua'))."','$this->x01_codrua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_codbairro"]))
           $resac = db_query("insert into db_acount values($acount,1426,8388,'".AddSlashes(pg_result($resaco,$conresaco,'x01_codbairro'))."','$this->x01_codbairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,1426,8389,'".AddSlashes(pg_result($resaco,$conresaco,'x01_numcgm'))."','$this->x01_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_promit"]))
           $resac = db_query("insert into db_acount values($acount,1426,8690,'".AddSlashes(pg_result($resaco,$conresaco,'x01_promit'))."','$this->x01_promit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_distrito"]))
           $resac = db_query("insert into db_acount values($acount,1426,8390,'".AddSlashes(pg_result($resaco,$conresaco,'x01_distrito'))."','$this->x01_distrito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_zona"]))
           $resac = db_query("insert into db_acount values($acount,1426,8391,'".AddSlashes(pg_result($resaco,$conresaco,'x01_zona'))."','$this->x01_zona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_quadra"]))
           $resac = db_query("insert into db_acount values($acount,1426,8392,'".AddSlashes(pg_result($resaco,$conresaco,'x01_quadra'))."','$this->x01_quadra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_numero"]))
           $resac = db_query("insert into db_acount values($acount,1426,8393,'".AddSlashes(pg_result($resaco,$conresaco,'x01_numero'))."','$this->x01_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_orientacao"]))
           $resac = db_query("insert into db_acount values($acount,1426,8394,'".AddSlashes(pg_result($resaco,$conresaco,'x01_orientacao'))."','$this->x01_orientacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_rota"]))
           $resac = db_query("insert into db_acount values($acount,1426,8395,'".AddSlashes(pg_result($resaco,$conresaco,'x01_rota'))."','$this->x01_rota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_dtcadastro"]))
           $resac = db_query("insert into db_acount values($acount,1426,8396,'".AddSlashes(pg_result($resaco,$conresaco,'x01_dtcadastro'))."','$this->x01_dtcadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_qtdponto"]))
           $resac = db_query("insert into db_acount values($acount,1426,8397,'".AddSlashes(pg_result($resaco,$conresaco,'x01_qtdponto'))."','$this->x01_qtdponto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_obs"]))
           $resac = db_query("insert into db_acount values($acount,1426,8398,'".AddSlashes(pg_result($resaco,$conresaco,'x01_obs'))."','$this->x01_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_multiplicador"]))
           $resac = db_query("insert into db_acount values($acount,1426,8441,'".AddSlashes(pg_result($resaco,$conresaco,'x01_multiplicador'))."','$this->x01_multiplicador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_qtdeconomia"]))
           $resac = db_query("insert into db_acount values($acount,1426,8443,'".AddSlashes(pg_result($resaco,$conresaco,'x01_qtdeconomia'))."','$this->x01_qtdeconomia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_entrega"]))
           $resac = db_query("insert into db_acount values($acount,1426,8530,'".AddSlashes(pg_result($resaco,$conresaco,'x01_entrega'))."','$this->x01_entrega',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x01_letra"]))
           $resac = db_query("insert into db_acount values($acount,1426,8709,'".AddSlashes(pg_result($resaco,$conresaco,'x01_letra'))."','$this->x01_letra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguabase nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x01_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguabase nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x01_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x01_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x01_matric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x01_matric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8386,'$x01_matric','E')");
         $resac = db_query("insert into db_acount values($acount,1426,8386,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8387,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_codrua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8388,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_codbairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8389,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8690,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_promit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8390,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_distrito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8391,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8392,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8393,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8394,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_orientacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8395,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_rota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8396,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_dtcadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8397,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_qtdponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8398,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8441,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_multiplicador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8443,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_qtdeconomia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8530,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_entrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1426,8709,'','".AddSlashes(pg_result($resaco,$iresaco,'x01_letra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguabase
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x01_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x01_matric = $x01_matric ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguabase nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x01_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguabase nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x01_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x01_matric;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguabase";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $x01_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       //$sql .= $campos;
			 $sql .= "aguabase.*, bairro.*, ruas.*, a.*, b.z01_nome as z01_nomepromit";
     }
     $sql .= " from aguabase ";
     $sql .= "      inner join bairro on  bairro.j13_codi = aguabase.x01_codbairro";
     $sql .= "      inner join ruas   on  ruas.j14_codigo = aguabase.x01_codrua";
     $sql .= "      inner join cgm a  on  a.z01_numcgm = aguabase.x01_numcgm";
     $sql .= "      left  join cgm b  on  b.z01_numcgm = aguabase.x01_promit";
     $sql2 = "";
     if($dbwhere==""){
       if($x01_matric!=null ){
         $sql2 .= " where aguabase.x01_matric = $x01_matric "; 
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

  public function sql_query_calculo_taxa_parcial($iAnoExercicio, $iMes, $iMatricula, $iTipoMovimento, $lTrocaNumpre, $lGeraFinanceiro) {

    $lGeraFinanceiro = $lGeraFinanceiro ? 'true': 'false';
    $lTrocaNumpre    = $lTrocaNumpre    ? 'true': 'false';

    $sSql = sprintf(
      "select fc_agua_calculoparcial(%s, %s, %s, %s, %s, %s);",
      $iAnoExercicio, $iMes, $iMatricula, $iTipoMovimento, $lTrocaNumpre, $lGeraFinanceiro
    );

    return $sSql;
  }

  public function sql_query_matriculas_ativas($sCampos, $sOrderBy = null, $sLimit = null) {

    $sSql  = "select {$sCampos} ";
    $sSql .= " from aguabase ";
    $sSql .= "    left join aguabasebaixa on x08_matric = x01_matric";
    $sSql .= " where x08_matric is null";

    if ($sOrderBy) {
      $sSql .= " order by {$sOrderBy} ";
    }

    if ($sLimit) {
      $sSql .= " limit {$sLimit} ";
    }

    return $sSql;
  }

   function sql_query_aguahidromatric ( $x01_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguabase ";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguabase.x01_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguabase.x01_codrua";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguabase.x01_numcgm";
     $sql .= "      left  join aguahidromatric  on  aguahidromatric.x04_matric = aguabase.x01_matric";
     $sql .= "      left  join aguaconstr  on  aguaconstr.x11_matric = aguabase.x01_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($x01_matric!=null ){
         $sql2 .= " where aguabase.x01_matric = $x01_matric "; 
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
   function sql_query_aguahidromatricpropri ( $x01_matric=null, $campos="*", $ordem=null, $dbwhere="" ){ 
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
     $sql .= " from aguabase ";
     $sql .= "      inner join proprietario_nome on j01_matric = aguabase.x01_matric";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguabase.x01_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguabase.x01_codrua";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguabase.x01_numcgm";
     $sql .= "      left  join aguaconstr on aguaconstr.x11_matric = aguabase.x01_matric and x11_tipo = 'P' ";
     $sql .= "      left  join aguaconstrcar on aguaconstrcar.x12_codconstr = aguaconstr.x11_codconstr";
     $sql .= "      left  join caracter  on  aguaconstrcar.x12_codigo = caracter.j31_codigo";
     $sql .= "      left  join aguahidromatric  on  aguahidromatric.x04_matric = aguabase.x01_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($x01_matric!=null ){
         $sql2 .= " where aguabase.x01_matric = $x01_matric "; 
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
   function sql_query_file ( $x01_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguabase ";
     $sql2 = "";
     if($dbwhere==""){
       if($x01_matric!=null ){
         $sql2 .= " where aguabase.x01_matric = $x01_matric "; 
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