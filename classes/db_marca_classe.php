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

//MODULO: marcas
//CLASSE DA ENTIDADE marca
class cl_marca { 
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
   var $ma01_i_codigo = 0; 
   var $ma01_i_cgm = 0; 
   var $ma01_d_data_dia = null; 
   var $ma01_d_data_mes = null; 
   var $ma01_d_data_ano = null; 
   var $ma01_d_data = null; 
   var $ma01_i_livro = 0; 
   var $ma01_i_folha = 0; 
   var $ma01_o_imagem = 0; 
   var $ma01_c_nomeimagem = null; 
   var $ma01_c_figura1 = null; 
   var $ma01_c_figura2 = null; 
   var $ma01_c_figura3 = null; 
   var $ma01_c_letra1 = null; 
   var $ma01_c_letra2 = null; 
   var $ma01_c_letra3 = null; 
   var $ma01_c_letra4 = null; 
   var $ma01_c_objeto1 = null; 
   var $ma01_c_objeto2 = null; 
   var $ma01_c_objeto3 = null; 
   var $ma01_c_ativo = null; 
   var $ma01_v_sinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ma01_i_codigo = int4 = Código da Marca 
                 ma01_i_cgm = int4 = Cgm do Proprietário 
                 ma01_d_data = date = Data 
                 ma01_i_livro = int4 = Livro 
                 ma01_i_folha = int4 = Folha 
                 ma01_o_imagem = oid = Imagem 
                 ma01_c_nomeimagem = char(50) = Nome da imagem 
                 ma01_c_figura1 = char(20) = Figura1 
                 ma01_c_figura2 = char(20) = Figura2 
                 ma01_c_figura3 = char(20) = Figura3 
                 ma01_c_letra1 = char(1) = Letra1/N 
                 ma01_c_letra2 = char(1) = Letra2/N 
                 ma01_c_letra3 = char(1) = Letra3/N 
                 ma01_c_letra4 = char(1) = Letra4/N 
                 ma01_c_objeto1 = char(20) = Objeto1 
                 ma01_c_objeto2 = char(20) = Objeto2 
                 ma01_c_objeto3 = char(20) = Objeto3 
                 ma01_c_ativo = char(1) = Marca Ativa 
                 ma01_v_sinal = varchar(60) = Sinal 
                 ";
   //funcao construtor da classe 
   function cl_marca() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("marca"); 
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
       $this->ma01_i_codigo = ($this->ma01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_i_codigo"]:$this->ma01_i_codigo);
       $this->ma01_i_cgm = ($this->ma01_i_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_i_cgm"]:$this->ma01_i_cgm);
       if($this->ma01_d_data == ""){
         $this->ma01_d_data_dia = ($this->ma01_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_d_data_dia"]:$this->ma01_d_data_dia);
         $this->ma01_d_data_mes = ($this->ma01_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_d_data_mes"]:$this->ma01_d_data_mes);
         $this->ma01_d_data_ano = ($this->ma01_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_d_data_ano"]:$this->ma01_d_data_ano);
         if($this->ma01_d_data_dia != ""){
            $this->ma01_d_data = $this->ma01_d_data_ano."-".$this->ma01_d_data_mes."-".$this->ma01_d_data_dia;
         }
       }
       $this->ma01_i_livro = ($this->ma01_i_livro == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_i_livro"]:$this->ma01_i_livro);
       $this->ma01_i_folha = ($this->ma01_i_folha == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_i_folha"]:$this->ma01_i_folha);
       $this->ma01_o_imagem = ($this->ma01_o_imagem == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_o_imagem"]:$this->ma01_o_imagem);
       $this->ma01_c_nomeimagem = ($this->ma01_c_nomeimagem == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_c_nomeimagem"]:$this->ma01_c_nomeimagem);
       $this->ma01_c_figura1 = ($this->ma01_c_figura1 == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_c_figura1"]:$this->ma01_c_figura1);
       $this->ma01_c_figura2 = ($this->ma01_c_figura2 == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_c_figura2"]:$this->ma01_c_figura2);
       $this->ma01_c_figura3 = ($this->ma01_c_figura3 == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_c_figura3"]:$this->ma01_c_figura3);
       $this->ma01_c_letra1 = ($this->ma01_c_letra1 == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_c_letra1"]:$this->ma01_c_letra1);
       $this->ma01_c_letra2 = ($this->ma01_c_letra2 == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_c_letra2"]:$this->ma01_c_letra2);
       $this->ma01_c_letra3 = ($this->ma01_c_letra3 == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_c_letra3"]:$this->ma01_c_letra3);
       $this->ma01_c_letra4 = ($this->ma01_c_letra4 == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_c_letra4"]:$this->ma01_c_letra4);
       $this->ma01_c_objeto1 = ($this->ma01_c_objeto1 == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_c_objeto1"]:$this->ma01_c_objeto1);
       $this->ma01_c_objeto2 = ($this->ma01_c_objeto2 == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_c_objeto2"]:$this->ma01_c_objeto2);
       $this->ma01_c_objeto3 = ($this->ma01_c_objeto3 == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_c_objeto3"]:$this->ma01_c_objeto3);
       $this->ma01_c_ativo = ($this->ma01_c_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_c_ativo"]:$this->ma01_c_ativo);
       $this->ma01_v_sinal = ($this->ma01_v_sinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_v_sinal"]:$this->ma01_v_sinal);
     }else{
       $this->ma01_i_codigo = ($this->ma01_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ma01_i_codigo"]:$this->ma01_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ma01_i_codigo){ 
      $this->atualizacampos();
     if($this->ma01_i_cgm == null ){ 
       $this->erro_sql = " Campo Cgm do Proprietário nao Informado.";
       $this->erro_campo = "ma01_i_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ma01_d_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "ma01_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ma01_i_livro == null ){ 
       $this->ma01_i_livro = "0";
     }
     if($this->ma01_i_folha == null ){ 
       $this->ma01_i_folha = "0";
     }
     if($this->ma01_o_imagem == null ){ 
       $this->erro_sql = " Campo Imagem nao Informado.";
       $this->erro_campo = "ma01_o_imagem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ma01_c_nomeimagem == null ){ 
       $this->erro_sql = " Campo Nome da imagem nao Informado.";
       $this->erro_campo = "ma01_c_nomeimagem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ma01_c_ativo == null ){ 
       $this->erro_sql = " Campo Marca Ativa nao Informado.";
       $this->erro_campo = "ma01_c_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ma01_i_codigo == "" || $ma01_i_codigo == null ){
       $result = db_query("select nextval('marca_ma01_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: marca_ma01_i_codigo_seq do campo: ma01_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ma01_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from marca_ma01_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ma01_i_codigo)){
         $this->erro_sql = " Campo ma01_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ma01_i_codigo = $ma01_i_codigo; 
       }
     }
     if(($this->ma01_i_codigo == null) || ($this->ma01_i_codigo == "") ){ 
       $this->erro_sql = " Campo ma01_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into marca(
                                       ma01_i_codigo 
                                      ,ma01_i_cgm 
                                      ,ma01_d_data 
                                      ,ma01_i_livro 
                                      ,ma01_i_folha 
                                      ,ma01_o_imagem 
                                      ,ma01_c_nomeimagem 
                                      ,ma01_c_figura1 
                                      ,ma01_c_figura2 
                                      ,ma01_c_figura3 
                                      ,ma01_c_letra1 
                                      ,ma01_c_letra2 
                                      ,ma01_c_letra3 
                                      ,ma01_c_letra4 
                                      ,ma01_c_objeto1 
                                      ,ma01_c_objeto2 
                                      ,ma01_c_objeto3 
                                      ,ma01_c_ativo 
                                      ,ma01_v_sinal 
                       )
                values (
                                $this->ma01_i_codigo 
                               ,$this->ma01_i_cgm 
                               ,".($this->ma01_d_data == "null" || $this->ma01_d_data == ""?"null":"'".$this->ma01_d_data."'")." 
                               ,$this->ma01_i_livro 
                               ,$this->ma01_i_folha 
                               ,$this->ma01_o_imagem 
                               ,'$this->ma01_c_nomeimagem' 
                               ,'$this->ma01_c_figura1' 
                               ,'$this->ma01_c_figura2' 
                               ,'$this->ma01_c_figura3' 
                               ,'$this->ma01_c_letra1' 
                               ,'$this->ma01_c_letra2' 
                               ,'$this->ma01_c_letra3' 
                               ,'$this->ma01_c_letra4' 
                               ,'$this->ma01_c_objeto1' 
                               ,'$this->ma01_c_objeto2' 
                               ,'$this->ma01_c_objeto3' 
                               ,'$this->ma01_c_ativo' 
                               ,'$this->ma01_v_sinal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "marca ($this->ma01_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "marca já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "marca ($this->ma01_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ma01_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ma01_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10500,'$this->ma01_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1815,10500,'','".AddSlashes(pg_result($resaco,0,'ma01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10501,'','".AddSlashes(pg_result($resaco,0,'ma01_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10502,'','".AddSlashes(pg_result($resaco,0,'ma01_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10503,'','".AddSlashes(pg_result($resaco,0,'ma01_i_livro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10504,'','".AddSlashes(pg_result($resaco,0,'ma01_i_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10505,'','".AddSlashes(pg_result($resaco,0,'ma01_o_imagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10506,'','".AddSlashes(pg_result($resaco,0,'ma01_c_nomeimagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10507,'','".AddSlashes(pg_result($resaco,0,'ma01_c_figura1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10508,'','".AddSlashes(pg_result($resaco,0,'ma01_c_figura2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10509,'','".AddSlashes(pg_result($resaco,0,'ma01_c_figura3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10510,'','".AddSlashes(pg_result($resaco,0,'ma01_c_letra1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10511,'','".AddSlashes(pg_result($resaco,0,'ma01_c_letra2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10512,'','".AddSlashes(pg_result($resaco,0,'ma01_c_letra3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10513,'','".AddSlashes(pg_result($resaco,0,'ma01_c_letra4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10514,'','".AddSlashes(pg_result($resaco,0,'ma01_c_objeto1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10515,'','".AddSlashes(pg_result($resaco,0,'ma01_c_objeto2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10516,'','".AddSlashes(pg_result($resaco,0,'ma01_c_objeto3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10517,'','".AddSlashes(pg_result($resaco,0,'ma01_c_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1815,10518,'','".AddSlashes(pg_result($resaco,0,'ma01_v_sinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ma01_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update marca set ";
     $virgula = "";
     if(trim($this->ma01_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_i_codigo"])){ 
       $sql  .= $virgula." ma01_i_codigo = $this->ma01_i_codigo ";
       $virgula = ",";
       if(trim($this->ma01_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código da Marca nao Informado.";
         $this->erro_campo = "ma01_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ma01_i_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_i_cgm"])){ 
       $sql  .= $virgula." ma01_i_cgm = $this->ma01_i_cgm ";
       $virgula = ",";
       if(trim($this->ma01_i_cgm) == null ){ 
         $this->erro_sql = " Campo Cgm do Proprietário nao Informado.";
         $this->erro_campo = "ma01_i_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ma01_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ma01_d_data_dia"] !="") ){ 
       $sql  .= $virgula." ma01_d_data = '$this->ma01_d_data' ";
       $virgula = ",";
       if(trim($this->ma01_d_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "ma01_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_d_data_dia"])){ 
         $sql  .= $virgula." ma01_d_data = null ";
         $virgula = ",";
         if(trim($this->ma01_d_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "ma01_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ma01_i_livro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_i_livro"])){ 
        if(trim($this->ma01_i_livro)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ma01_i_livro"])){ 
           $this->ma01_i_livro = "0" ; 
        } 
       $sql  .= $virgula." ma01_i_livro = $this->ma01_i_livro ";
       $virgula = ",";
     }
     if(trim($this->ma01_i_folha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_i_folha"])){ 
        if(trim($this->ma01_i_folha)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ma01_i_folha"])){ 
           $this->ma01_i_folha = "0" ; 
        } 
       $sql  .= $virgula." ma01_i_folha = $this->ma01_i_folha ";
       $virgula = ",";
     }
     if(trim($this->ma01_o_imagem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_o_imagem"])){ 
       $sql  .= $virgula." ma01_o_imagem = $this->ma01_o_imagem ";
       $virgula = ",";
       if(trim($this->ma01_o_imagem) == null ){ 
         $this->erro_sql = " Campo Imagem nao Informado.";
         $this->erro_campo = "ma01_o_imagem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ma01_c_nomeimagem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_nomeimagem"])){ 
       $sql  .= $virgula." ma01_c_nomeimagem = '$this->ma01_c_nomeimagem' ";
       $virgula = ",";
       if(trim($this->ma01_c_nomeimagem) == null ){ 
         $this->erro_sql = " Campo Nome da imagem nao Informado.";
         $this->erro_campo = "ma01_c_nomeimagem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ma01_c_figura1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_figura1"])){ 
       $sql  .= $virgula." ma01_c_figura1 = '$this->ma01_c_figura1' ";
       $virgula = ",";
     }
     if(trim($this->ma01_c_figura2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_figura2"])){ 
       $sql  .= $virgula." ma01_c_figura2 = '$this->ma01_c_figura2' ";
       $virgula = ",";
     }
     if(trim($this->ma01_c_figura3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_figura3"])){ 
       $sql  .= $virgula." ma01_c_figura3 = '$this->ma01_c_figura3' ";
       $virgula = ",";
     }
     if(trim($this->ma01_c_letra1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_letra1"])){ 
       $sql  .= $virgula." ma01_c_letra1 = '$this->ma01_c_letra1' ";
       $virgula = ",";
     }
     if(trim($this->ma01_c_letra2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_letra2"])){ 
       $sql  .= $virgula." ma01_c_letra2 = '$this->ma01_c_letra2' ";
       $virgula = ",";
     }
     if(trim($this->ma01_c_letra3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_letra3"])){ 
       $sql  .= $virgula." ma01_c_letra3 = '$this->ma01_c_letra3' ";
       $virgula = ",";
     }
     if(trim($this->ma01_c_letra4)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_letra4"])){ 
       $sql  .= $virgula." ma01_c_letra4 = '$this->ma01_c_letra4' ";
       $virgula = ",";
     }
     if(trim($this->ma01_c_objeto1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_objeto1"])){ 
       $sql  .= $virgula." ma01_c_objeto1 = '$this->ma01_c_objeto1' ";
       $virgula = ",";
     }
     if(trim($this->ma01_c_objeto2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_objeto2"])){ 
       $sql  .= $virgula." ma01_c_objeto2 = '$this->ma01_c_objeto2' ";
       $virgula = ",";
     }
     if(trim($this->ma01_c_objeto3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_objeto3"])){ 
       $sql  .= $virgula." ma01_c_objeto3 = '$this->ma01_c_objeto3' ";
       $virgula = ",";
     }
     if(trim($this->ma01_c_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_ativo"])){ 
       $sql  .= $virgula." ma01_c_ativo = '$this->ma01_c_ativo' ";
       $virgula = ",";
       if(trim($this->ma01_c_ativo) == null ){ 
         $this->erro_sql = " Campo Marca Ativa nao Informado.";
         $this->erro_campo = "ma01_c_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ma01_v_sinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ma01_v_sinal"])){ 
       $sql  .= $virgula." ma01_v_sinal = '$this->ma01_v_sinal' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ma01_i_codigo!=null){
       $sql .= " ma01_i_codigo = $this->ma01_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ma01_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10500,'$this->ma01_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1815,10500,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_i_codigo'))."','$this->ma01_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_i_cgm"]))
           $resac = db_query("insert into db_acount values($acount,1815,10501,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_i_cgm'))."','$this->ma01_i_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_d_data"]))
           $resac = db_query("insert into db_acount values($acount,1815,10502,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_d_data'))."','$this->ma01_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_i_livro"]))
           $resac = db_query("insert into db_acount values($acount,1815,10503,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_i_livro'))."','$this->ma01_i_livro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_i_folha"]))
           $resac = db_query("insert into db_acount values($acount,1815,10504,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_i_folha'))."','$this->ma01_i_folha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_o_imagem"]))
           $resac = db_query("insert into db_acount values($acount,1815,10505,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_o_imagem'))."','$this->ma01_o_imagem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_nomeimagem"]))
           $resac = db_query("insert into db_acount values($acount,1815,10506,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_c_nomeimagem'))."','$this->ma01_c_nomeimagem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_figura1"]))
           $resac = db_query("insert into db_acount values($acount,1815,10507,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_c_figura1'))."','$this->ma01_c_figura1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_figura2"]))
           $resac = db_query("insert into db_acount values($acount,1815,10508,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_c_figura2'))."','$this->ma01_c_figura2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_figura3"]))
           $resac = db_query("insert into db_acount values($acount,1815,10509,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_c_figura3'))."','$this->ma01_c_figura3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_letra1"]))
           $resac = db_query("insert into db_acount values($acount,1815,10510,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_c_letra1'))."','$this->ma01_c_letra1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_letra2"]))
           $resac = db_query("insert into db_acount values($acount,1815,10511,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_c_letra2'))."','$this->ma01_c_letra2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_letra3"]))
           $resac = db_query("insert into db_acount values($acount,1815,10512,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_c_letra3'))."','$this->ma01_c_letra3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_letra4"]))
           $resac = db_query("insert into db_acount values($acount,1815,10513,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_c_letra4'))."','$this->ma01_c_letra4',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_objeto1"]))
           $resac = db_query("insert into db_acount values($acount,1815,10514,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_c_objeto1'))."','$this->ma01_c_objeto1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_objeto2"]))
           $resac = db_query("insert into db_acount values($acount,1815,10515,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_c_objeto2'))."','$this->ma01_c_objeto2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_objeto3"]))
           $resac = db_query("insert into db_acount values($acount,1815,10516,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_c_objeto3'))."','$this->ma01_c_objeto3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_c_ativo"]))
           $resac = db_query("insert into db_acount values($acount,1815,10517,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_c_ativo'))."','$this->ma01_c_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ma01_v_sinal"]))
           $resac = db_query("insert into db_acount values($acount,1815,10518,'".AddSlashes(pg_result($resaco,$conresaco,'ma01_v_sinal'))."','$this->ma01_v_sinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "marca nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ma01_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "marca nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ma01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ma01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ma01_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ma01_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10500,'$ma01_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1815,10500,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10501,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_i_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10502,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10503,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_i_livro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10504,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_i_folha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10505,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_o_imagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10506,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_c_nomeimagem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10507,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_c_figura1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10508,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_c_figura2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10509,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_c_figura3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10510,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_c_letra1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10511,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_c_letra2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10512,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_c_letra3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10513,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_c_letra4'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10514,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_c_objeto1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10515,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_c_objeto2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10516,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_c_objeto3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10517,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_c_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1815,10518,'','".AddSlashes(pg_result($resaco,$iresaco,'ma01_v_sinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from marca
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ma01_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ma01_i_codigo = $ma01_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "marca nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ma01_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "marca nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ma01_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ma01_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:marca";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ma01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from marca ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = marca.ma01_i_cgm";
     $sql2 = "";
     if($dbwhere==""){
       if($ma01_i_codigo!=null ){
         $sql2 .= " where marca.ma01_i_codigo = $ma01_i_codigo "; 
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
   function sql_query_file ( $ma01_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from marca ";
     $sql2 = "";
     if($dbwhere==""){
       if($ma01_i_codigo!=null ){
         $sql2 .= " where marca.ma01_i_codigo = $ma01_i_codigo "; 
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